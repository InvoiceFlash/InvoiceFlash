<?php
class ModelCommonCalendar extends Model {
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "crm_calendar` (
			`calendar_id` int(11) NOT NULL AUTO_INCREMENT,
			`user_id` int(11) NOT NULL,
			`name` varchar(96) NOT NULL DEFAULT '',
			`color` varchar(7) NOT NULL DEFAULT '#3788d8',
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`calendar_id`),
			KEY `user_id` (`user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "crm_calendar_event` (
			`event_id` int(11) NOT NULL AUTO_INCREMENT,
			`calendar_id` int(11) NOT NULL,
			`title` varchar(255) NOT NULL DEFAULT '',
			`location` varchar(255) NOT NULL DEFAULT '',
			`description` text NULL,
			`start` datetime NOT NULL,
			`end` datetime NULL DEFAULT NULL,
			`all_day` tinyint(1) NOT NULL DEFAULT 0,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`event_id`),
			KEY `calendar_id` (`calendar_id`),
			KEY `start` (`start`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "crm_calendar_user` (
			`calendar_id` int(11) NOT NULL,
			`user_id` int(11) NOT NULL,
			PRIMARY KEY (`calendar_id`,`user_id`),
			KEY `user_id` (`user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}

	// Condicion SQL: el usuario es propietario del calendario o lo tiene compartido.
	private function access($user_id, $alias = 'c') {
		$user_id = (int)$user_id;

		return "(" . $alias . ".user_id = '" . $user_id . "' OR " . $alias . ".calendar_id IN (SELECT calendar_id FROM `" . DB_PREFIX . "crm_calendar_user` WHERE user_id = '" . $user_id . "'))";
	}

	public function getCalendars($user_id) {
		$sql = "SELECT c.*, (c.user_id = '" . (int)$user_id . "') AS is_owner FROM `" . DB_PREFIX . "crm_calendar` c WHERE " . $this->access($user_id) . " ORDER BY c.name";
		$query = $this->db->query($sql);

		if (!$query->num_rows) {
			$this->addCalendar($user_id, 'Personal', '#3788d8');
			$query = $this->db->query($sql);
		}

		$rows = array();

		foreach ($query->rows as $row) {
			$row['is_owner'] = (int)$row['is_owner'];
			$row['shared'] = $this->getShares($row['calendar_id']);
			$rows[] = $row;
		}

		return $rows;
	}

	// Calendario al que el usuario tiene acceso (propio o compartido): para eventos.
	public function getCalendar($calendar_id, $user_id) {
		$query = $this->db->query("SELECT c.* FROM `" . DB_PREFIX . "crm_calendar` c WHERE c.calendar_id = '" . (int)$calendar_id . "' AND " . $this->access($user_id));

		return $query->row;
	}

	// Solo el propietario puede editar, borrar y compartir el calendario.
	public function getOwnCalendar($calendar_id, $user_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "crm_calendar` WHERE calendar_id = '" . (int)$calendar_id . "' AND user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getShares($calendar_id) {
		$ids = array();

		foreach ($this->db->query("SELECT user_id FROM `" . DB_PREFIX . "crm_calendar_user` WHERE calendar_id = '" . (int)$calendar_id . "'")->rows as $row) {
			$ids[] = (int)$row['user_id'];
		}

		return $ids;
	}

	public function setShares($calendar_id, $user_ids, $owner_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "crm_calendar_user` WHERE calendar_id = '" . (int)$calendar_id . "'");

		foreach (array_unique(array_map('intval', (array)$user_ids)) as $uid) {
			if ($uid > 0 && $uid != (int)$owner_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "crm_calendar_user` SET calendar_id = '" . (int)$calendar_id . "', user_id = '" . $uid . "'");
			}
		}
	}

	public function getUsers() {
		return $this->db->query("SELECT user_id, username FROM `" . DB_PREFIX . "user` ORDER BY username")->rows;
	}

	public function addCalendar($user_id, $name, $color) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "crm_calendar` SET user_id = '" . (int)$user_id . "', name = '" . $this->db->escape($name) . "', color = '" . $this->db->escape($this->cleanColor($color)) . "', date_added = NOW()");

		return $this->db->getLastId();
	}

	public function editCalendar($calendar_id, $name, $color) {
		$this->db->query("UPDATE `" . DB_PREFIX . "crm_calendar` SET name = '" . $this->db->escape($name) . "', color = '" . $this->db->escape($this->cleanColor($color)) . "' WHERE calendar_id = '" . (int)$calendar_id . "'");
	}

	public function deleteCalendar($calendar_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "crm_calendar_event` WHERE calendar_id = '" . (int)$calendar_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "crm_calendar_user` WHERE calendar_id = '" . (int)$calendar_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "crm_calendar` WHERE calendar_id = '" . (int)$calendar_id . "'");
	}

	public function getEvents($user_id, $start, $end) {
		$query = $this->db->query("SELECT e.*, c.color FROM `" . DB_PREFIX . "crm_calendar_event` e INNER JOIN `" . DB_PREFIX . "crm_calendar` c ON c.calendar_id = e.calendar_id WHERE " . $this->access($user_id) . " AND e.start < '" . $this->db->escape($end) . "' AND COALESCE(e.`end`, e.start) >= '" . $this->db->escape($start) . "'");

		return $query->rows;
	}

	// Devuelve el evento solo si pertenece a un calendario del usuario.
	public function getEvent($event_id, $user_id) {
		$query = $this->db->query("SELECT e.* FROM `" . DB_PREFIX . "crm_calendar_event` e INNER JOIN `" . DB_PREFIX . "crm_calendar` c ON c.calendar_id = e.calendar_id WHERE e.event_id = '" . (int)$event_id . "' AND " . $this->access($user_id) . "");

		return $query->row;
	}

	public function addEvent($d) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "crm_calendar_event` SET calendar_id = '" . (int)$d['calendar_id'] . "', title = '" . $this->db->escape($d['title']) . "', location = '" . $this->db->escape($d['location']) . "', description = '" . $this->db->escape($d['description']) . "', start = '" . $this->db->escape($d['start']) . "', `end` = " . ($d['end'] !== '' ? "'" . $this->db->escape($d['end']) . "'" : 'NULL') . ", all_day = '" . (int)$d['all_day'] . "', date_added = NOW()");

		return $this->db->getLastId();
	}

	public function editEvent($event_id, $d) {
		$this->db->query("UPDATE `" . DB_PREFIX . "crm_calendar_event` SET calendar_id = '" . (int)$d['calendar_id'] . "', title = '" . $this->db->escape($d['title']) . "', location = '" . $this->db->escape($d['location']) . "', description = '" . $this->db->escape($d['description']) . "', start = '" . $this->db->escape($d['start']) . "', `end` = " . ($d['end'] !== '' ? "'" . $this->db->escape($d['end']) . "'" : 'NULL') . ", all_day = '" . (int)$d['all_day'] . "' WHERE event_id = '" . (int)$event_id . "'");
	}

	public function moveEvent($event_id, $start, $end, $all_day) {
		$this->db->query("UPDATE `" . DB_PREFIX . "crm_calendar_event` SET start = '" . $this->db->escape($start) . "', `end` = " . ($end !== '' ? "'" . $this->db->escape($end) . "'" : 'NULL') . ", all_day = '" . (int)$all_day . "' WHERE event_id = '" . (int)$event_id . "'");
	}

	public function deleteEvent($event_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "crm_calendar_event` WHERE event_id = '" . (int)$event_id . "'");
	}

	private function cleanColor($color) {
		return preg_match('/^#[0-9a-fA-F]{6}$/', $color) ? $color : '#3788d8';
	}
}

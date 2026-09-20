<?php
class ControllerCommonCalendar extends Controller {
	public function index() {
		$this->load->language('common/calendar');
		$this->load->model('common/calendar');

		$this->model_common_calendar->install();

		$this->document->setTitle($this->language->get('heading_title'));

		foreach (array('heading_title', 'text_new_event', 'text_edit_event', 'text_calendars', 'text_new_calendar', 'text_confirm', 'text_today', 'text_month', 'text_week', 'text_day', 'text_list', 'entry_title', 'entry_calendar', 'entry_start', 'entry_end', 'entry_all_day', 'entry_location', 'entry_description', 'entry_name', 'entry_color', 'button_save', 'button_delete', 'button_close', 'error_title') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['calendars'] = $this->model_common_calendar->getCalendars($this->user->getId());
		$this->data['can_modify'] = true;
		$this->data['locale'] = substr($this->config->get('config_admin_language') ? $this->config->get('config_admin_language') : 'es', 0, 2);

		$token = $this->session->data['token'];
		$this->data['url_events']   = html_entity_decode($this->url->link('common/calendar/events', 'token=' . $token, 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['url_save']     = html_entity_decode($this->url->link('common/calendar/saveEvent', 'token=' . $token, 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['url_move']     = html_entity_decode($this->url->link('common/calendar/moveEvent', 'token=' . $token, 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['url_delete']   = html_entity_decode($this->url->link('common/calendar/deleteEvent', 'token=' . $token, 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['url_cal_save'] = html_entity_decode($this->url->link('common/calendar/saveCalendar', 'token=' . $token, 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['url_cal_del']  = html_entity_decode($this->url->link('common/calendar/deleteCalendar', 'token=' . $token, 'SSL'), ENT_QUOTES, 'UTF-8');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $token, 'SSL')
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/calendar', 'token=' . $token, 'SSL')
		);

		$this->template = 'common/calendar.tpl';
		$this->children = array('common/header', 'common/footer');

		$this->response->setOutput($this->render());
	}

	// JSON de eventos para FullCalendar (parametros start/end en ISO 8601).
	public function events() {
		$this->load->model('common/calendar');

		$start = $this->toSqlDate(isset($this->request->get['start']) ? $this->request->get['start'] : '');
		$end   = $this->toSqlDate(isset($this->request->get['end']) ? $this->request->get['end'] : '');
		$out   = array();

		if (true && $start && $end) {
			foreach ($this->model_common_calendar->getEvents($this->user->getId(), $start, $end) as $e) {
				$all_day = (int)$e['all_day'] === 1;

				$out[] = array(
					'id'              => (int)$e['event_id'],
					'title'           => html_entity_decode($e['title'], ENT_QUOTES, 'UTF-8'),
					'start'           => $all_day ? substr($e['start'], 0, 10) : str_replace(' ', 'T', $e['start']),
					'end'             => $e['end'] ? ($all_day ? substr($e['end'], 0, 10) : str_replace(' ', 'T', $e['end'])) : null,
					'allDay'          => $all_day,
					'backgroundColor' => $e['color'],
					'borderColor'     => $e['color'],
					'extendedProps'   => array(
						'calendar_id' => (int)$e['calendar_id'],
						'location'    => html_entity_decode($e['location'], ENT_QUOTES, 'UTF-8'),
						'description' => html_entity_decode((string)$e['description'], ENT_QUOTES, 'UTF-8')
					)
				);
			}
		}

		$this->json($out);
	}

	public function saveEvent() {
		$this->load->language('common/calendar');
		$this->load->model('common/calendar');

		if (!true) {
			return $this->json(array('error' => $this->language->get('error_permission')));
		}

		$p = $this->request->post;
		$title = isset($p['title']) ? trim($p['title']) : '';
		$calendar = $this->model_common_calendar->getCalendar(isset($p['calendar_id']) ? $p['calendar_id'] : 0, $this->user->getId());
		$all_day = !empty($p['all_day']) ? 1 : 0;
		$start = $this->toSqlDate(isset($p['start']) ? $p['start'] : '');
		$end = $this->toSqlDate(isset($p['end']) ? $p['end'] : '');

		if ($title === '' || !$calendar || !$start) {
			return $this->json(array('error' => $this->language->get('error_title')));
		}

		$data = array(
			'calendar_id' => $calendar['calendar_id'],
			'title'       => $title,
			'location'    => isset($p['location']) ? $p['location'] : '',
			'description' => isset($p['description']) ? $p['description'] : '',
			'start'       => $start,
			'end'         => $end ? $end : '',
			'all_day'     => $all_day
		);

		$event_id = isset($p['event_id']) ? (int)$p['event_id'] : 0;

		if ($event_id) {
			if (!$this->model_common_calendar->getEvent($event_id, $this->user->getId())) {
				return $this->json(array('error' => $this->language->get('error_permission')));
			}

			$this->model_common_calendar->editEvent($event_id, $data);
		} else {
			$this->model_common_calendar->addEvent($data);
		}

		$this->json(array('success' => true));
	}

	// Arrastrar/redimensionar un evento en el calendario.
	public function moveEvent() {
		$this->load->model('common/calendar');

		$p = $this->request->post;
		$event_id = isset($p['event_id']) ? (int)$p['event_id'] : 0;
		$start = $this->toSqlDate(isset($p['start']) ? $p['start'] : '');
		$end = $this->toSqlDate(isset($p['end']) ? $p['end'] : '');

		if (!true || !$start || !$this->model_common_calendar->getEvent($event_id, $this->user->getId())) {
			return $this->json(array('error' => 1));
		}

		$this->model_common_calendar->moveEvent($event_id, $start, $end ? $end : '', !empty($p['all_day']) ? 1 : 0);

		$this->json(array('success' => true));
	}

	public function deleteEvent() {
		$this->load->model('common/calendar');

		$event_id = isset($this->request->post['event_id']) ? (int)$this->request->post['event_id'] : 0;

		if (!true || !$this->model_common_calendar->getEvent($event_id, $this->user->getId())) {
			return $this->json(array('error' => 1));
		}

		$this->model_common_calendar->deleteEvent($event_id);

		$this->json(array('success' => true));
	}

	public function saveCalendar() {
		$this->load->model('common/calendar');

		$p = $this->request->post;
		$name = isset($p['name']) ? trim($p['name']) : '';

		if (!true || $name === '') {
			return $this->json(array('error' => 1));
		}

		$calendar_id = isset($p['calendar_id']) ? (int)$p['calendar_id'] : 0;
		$color = isset($p['color']) ? $p['color'] : '';

		if ($calendar_id) {
			if (!$this->model_common_calendar->getCalendar($calendar_id, $this->user->getId())) {
				return $this->json(array('error' => 1));
			}

			$this->model_common_calendar->editCalendar($calendar_id, $name, $color);
		} else {
			$this->model_common_calendar->addCalendar($this->user->getId(), $name, $color);
		}

		$this->json(array('success' => true));
	}

	public function deleteCalendar() {
		$this->load->model('common/calendar');

		$calendar_id = isset($this->request->post['calendar_id']) ? (int)$this->request->post['calendar_id'] : 0;

		if (!true || !$this->model_common_calendar->getCalendar($calendar_id, $this->user->getId())) {
			return $this->json(array('error' => 1));
		}

		$this->model_common_calendar->deleteCalendar($calendar_id);

		$this->json(array('success' => true));
	}

	// "2026-09-20T10:30:00+02:00" / "2026-09-20" -> "2026-09-20 10:30:00" (hora local tal cual, sin zona).
	private function toSqlDate($value) {
		if (!preg_match('/^(\d{4}-\d{2}-\d{2})(?:[T ](\d{2}:\d{2})(?::(\d{2}))?)?/', (string)$value, $m)) {
			return '';
		}

		return $m[1] . ' ' . (isset($m[2]) ? $m[2] . ':' . (isset($m[3]) ? $m[3] : '00') : '00:00:00');
	}

	private function json($data) {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
}

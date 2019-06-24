<?php
class Log {
	private $filename;
	
	public function __construct($filename) {
		$this->filename = $filename;
	}
	
	public function write($message) {
		$file = DIR_LOGS . $this->filename;
		
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true)  . "\n");
			
		fclose($handle); 
	}
	
	public function logChanges($id, $data, $tables) {
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		// $filename sera la tabla principal
		$parent_table = $this->filename;
		array_push($tables, $parent_table); 

		// $cols = $this->db->query("SELECT DISTINCT `COLUMN_NAME` FROM `information_schema`.`COLUMNS` WHERE `TABLE_NAME` LIKE '$table'"); - Servidor Localhost
		foreach ($tables as $table) {
			// campos de la tabla
			$fields = $db->query("SHOW COLUMNS FROM " . DB_PREFIX . $table);
			
			// datos anteriores de la tabla
			$qry = $db->query("SELECT * FROM `" . DB_PREFIX . $table . "` WHERE " . $parent_table . "_id = " . $id);
			$old_data = $qry->rows;
			
			$changes = array();
			$line = 0;

			if ($table === $parent_table) {
				$new_data = $data;

				foreach ($new_data as $field => $value) {
					if ($value != $old_data[$line][$field] && (!is_array($value))) {
						$changes[] = array(
							'table' => $table,
							'id' => $id,
							'field' => $field,
							'old' => $old_data[$line][$field],
							'new' => $value
						);
					}
				}
			} else {
				$new_data = $data[$table];
				
				foreach ($new_data as $sub_id => $row) {
					
					foreach ($row as $field => $value) {
						if ($new_data[$sub_id][$field] != $old_data[$line][$field] && (!is_array($value))) {
							$changes[] = array(
								'table' => $table,
								'field' => $field,
								'old' => $old_data[$line][$field],
								'new' => $new_data[$sub_id][$field]
							);
						}
					}
					$line++;
				}
			}

			foreach ($changes as $change) {
				$sql = "INSERT INTO `" . DB_PREFIX . "log` SET table_name = '" . $db->escape($change['table']) . "', 
					`field` = '" . $db->escape($change['field']) . "',
					`old_value` = '" . $db->escape($change['old']) . "', 
					`new_value` = '" . $db->escape($change['new']) . "', 
					`parent_table` = '" . $db->escape($parent_table) . "', 
					`parent_table_id` = '" . (int)$id . "', 
					`date_added` = now()";

				$db->query($sql);
			}
		}
	}
}
?>
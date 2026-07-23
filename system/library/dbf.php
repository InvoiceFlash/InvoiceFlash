<?php
/**
 * Lector de ficheros dBase/FoxPro (.dbf), como los que genera SaConta.
 * Solo lectura. Soporta los tipos de campo C (texto), N (num&eacute;rico),
 * D (fecha), L (l&oacute;gico) y T (timestamp, devuelto en crudo).
 */
class Dbf {
	private $handle;
	private $records;
	private $headerLength;
	private $recordLength;
	private $fields = array();

	public function __construct($path) {
		if (!is_readable($path)) {
			throw new Exception('File not readable: ' . $path);
		}

		$this->handle = @fopen($path, 'rb');

		if (!$this->handle) {
			throw new Exception('File could not be opened (locked?): ' . $path);
		}

		$header = fread($this->handle, 32);
		$unpacked = unpack('Cversion/Cyear/Cmonth/Cday/Vrecords/vheaderlen/vrecordlen', $header);

		$this->records      = $unpacked['records'];
		$this->headerLength = $unpacked['headerlen'];
		$this->recordLength = $unpacked['recordlen'];

		while (true) {
			$marker = fread($this->handle, 1);

			if ($marker === "\x0D" || $marker === false || feof($this->handle)) {
				break;
			}

			$descriptor = $marker . fread($this->handle, 31);

			$this->fields[] = array(
				'name'     => rtrim(substr($descriptor, 0, 11), "\x00"),
				'type'     => substr($descriptor, 11, 1),
				'length'   => ord(substr($descriptor, 16, 1)),
				'decimals' => ord(substr($descriptor, 17, 1))
			);
		}
	}

	public function getFieldNames() {
		return array_column($this->fields, 'name');
	}

	public function getRecordCount() {
		return $this->records;
	}

	// Generador: recorre las filas no borradas, ya convertidas a tipos PHP y UTF-8.
	public function rows() {
		fseek($this->handle, $this->headerLength);

		for ($i = 0; $i < $this->records; $i++) {
			$record = fread($this->handle, $this->recordLength);

			if ($record === false || strlen($record) < $this->recordLength) {
				break;
			}

			if ($record[0] === '*') {
				continue; // registro marcado como borrado
			}

			$offset = 1;
			$row = array();

			foreach ($this->fields as $field) {
				$raw = substr($record, $offset, $field['length']);
				$offset += $field['length'];

				$row[$field['name']] = $this->castValue($raw, $field);
			}

			yield $row;
		}
	}

	private function castValue($raw, $field) {
		switch ($field['type']) {
			case 'N':
				$value = trim($raw);

				if ($value === '') {
					return 0;
				}

				return $field['decimals'] > 0 ? (float)$value : (int)$value;

			case 'D':
				$value = trim($raw);

				if (strlen($value) !== 8) {
					return null;
				}

				return substr($value, 0, 4) . '-' . substr($value, 4, 2) . '-' . substr($value, 6, 2);

			case 'L':
				$value = strtoupper(trim($raw));

				return ($value === 'T' || $value === 'Y');

			case 'C':
			default:
				$value = rtrim($raw);

				return iconv('CP1252', 'UTF-8//IGNORE', $value);
		}
	}

	public function __destruct() {
		if ($this->handle) {
			fclose($this->handle);
		}
	}
}

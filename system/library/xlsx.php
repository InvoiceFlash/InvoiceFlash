<?php
class Xlsx {
	private $headers = array();
	private $rows = array();

	public function setHeaders($headers) {
		$this->headers = $headers;
	}

	public function addRow($row) {
		$this->rows[] = $row;
	}

	public function build($sheet_name = 'Sheet1') {
		$tmp_file = tempnam(sys_get_temp_dir(), 'xlsx');

		$zip = new ZipArchive();
		$zip->open($tmp_file, ZipArchive::OVERWRITE);

		$zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
		$zip->addFromString('_rels/.rels', $this->relsXml());
		$zip->addFromString('xl/workbook.xml', $this->workbookXml($sheet_name));
		$zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml());
		$zip->addFromString('xl/styles.xml', $this->stylesXml());
		$zip->addFromString('xl/worksheets/sheet1.xml', $this->sheetXml());

		$zip->close();

		$content = file_get_contents($tmp_file);

		unlink($tmp_file);

		return $content;
	}

	private function escape($value) {
		return htmlspecialchars((string)$value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
	}

	private function cellRef($col, $row) {
		$letter = '';

		while ($col > 0) {
			$mod = ($col - 1) % 26;
			$letter = chr(65 + $mod) . $letter;
			$col = (int)(($col - $mod) / 26);
		}

		return $letter . $row;
	}

	private function sheetXml() {
		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';

		$row_index = 1;

		$xml .= '<row r="' . $row_index . '">';

		$col = 0;

		foreach ($this->headers as $header) {
			$col++;
			$xml .= '<c r="' . $this->cellRef($col, $row_index) . '" t="inlineStr" s="1"><is><t xml:space="preserve">' . $this->escape($header) . '</t></is></c>';
		}

		$xml .= '</row>';

		foreach ($this->rows as $row) {
			$row_index++;
			$xml .= '<row r="' . $row_index . '">';

			$col = 0;

			foreach ($row as $value) {
				$col++;
				$xml .= '<c r="' . $this->cellRef($col, $row_index) . '" t="inlineStr"><is><t xml:space="preserve">' . $this->escape($value) . '</t></is></c>';
			}

			$xml .= '</row>';
		}

		$xml .= '</sheetData></worksheet>';

		return $xml;
	}

	private function contentTypesXml() {
		return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
			. '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
			. '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
			. '<Default Extension="xml" ContentType="application/xml"/>'
			. '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
			. '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
			. '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
			. '</Types>';
	}

	private function relsXml() {
		return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
			. '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
			. '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
			. '</Relationships>';
	}

	private function workbookXml($sheet_name) {
		return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
			. '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
			. '<sheets><sheet name="' . $this->escape($sheet_name) . '" sheetId="1" r:id="rId1"/></sheets>'
			. '</workbook>';
	}

	private function workbookRelsXml() {
		return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
			. '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
			. '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
			. '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
			. '</Relationships>';
	}

	private function stylesXml() {
		return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
			. '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
			. '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
			. '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
			. '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
			. '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
			. '<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/></cellXfs>'
			. '</styleSheet>';
	}
}
?>

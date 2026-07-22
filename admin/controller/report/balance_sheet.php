<?php
class ControllerReportBalanceSheet extends Controller {
	public function index() {
		$this->language->load('report/balance_sheet');

		$this->document->setTitle($this->language->get('heading_title'));

		$filter_date_end = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : date('d-m-Y');

		$url = $this->buildUrl();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/balance_sheet', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/balance_sheet');

		$this->data['statements'] = $this->buildStatements($this->toSqlDate($filter_date_end));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_period']     = $this->language->get('text_period');
		$this->data['text_as_of']      = $this->language->get('text_as_of');
		$this->data['text_company']    = $this->language->get('text_company');
		$this->data['text_print_date'] = $this->language->get('text_print_date');
		$this->data['text_total']      = $this->language->get('text_total');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_print']  = $this->language->get('button_print');

		$this->data['filter_date_end'] = $filter_date_end;

		$this->data['company_name'] = $this->config->get('config_name');
		$this->data['print_date']   = date($this->language->get('date_format_short'));

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'report/balance_sheet_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->language->load('report/balance_sheet');

		if (!$this->user->hasPermission('access', 'report/balance_sheet')) {
			die('Permission denied');
		}

		$filter_date_end = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';

		$this->load->model('report/balance_sheet');

		$statements = $this->buildStatements($this->toSqlDate($filter_date_end));

		$csv  = "\xEF\xBB\xBF"; // UTF-8 BOM para Excel
		$csv .= '"Bloque";"Seccion";"Grupo";"Cuenta";"Titulo";"Importe"' . "\n";

		foreach ($statements as $statement) {
			foreach ($statement['sections'] as $section) {
				foreach ($section['groups'] as $group) {
					foreach ($group['rows'] as $row) {
						$csv .= '"' . str_replace('"', '""', (string)$statement['title'])   . '";';
						$csv .= '"' . str_replace('"', '""', (string)$section['label'])     . '";';
						$csv .= '"' . str_replace('"', '""', (string)$group['label'])       . '";';
						$csv .= '"' . str_replace('"', '""', (string)$row['code'])          . '";';
						$csv .= '"' . str_replace('"', '""', (string)$row['title'])         . '";';
						$csv .= '"' . str_replace('"', '""', (string)$row['amount'])        . '"' . "\n";
					}
				}
			}
		}

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="balance_situacion_' . date('Y-m-d') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
	}

	public function printout() {
		$this->language->load('report/balance_sheet');

		$this->load->model('report/balance_sheet');

		$format = isset($this->request->get['format']) ? $this->request->get['format'] : 'view';

		$filter_date_end = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';

		$this->data['statements'] = $this->buildStatements($this->toSqlDate($filter_date_end));

		$date_format = $this->language->get('date_format_short');

		$this->data['heading_title']   = $this->language->get('heading_title');
		$this->data['text_company']    = $this->language->get('text_company');
		$this->data['text_as_of']      = $this->language->get('text_as_of');
		$this->data['text_print_date'] = $this->language->get('text_print_date');
		$this->data['text_total']      = $this->language->get('text_total');

		$this->data['company_name'] = $this->config->get('config_name');

		$sql_date = $this->toSqlDate($filter_date_end);

		$this->data['as_of'] = $sql_date ? date($date_format, strtotime($sql_date)) : '';

		$this->data['print_date'] = date($date_format);

		if ($format == 'pdf') {
			$this->renderPDF('report/balance_sheet_print.tpl', 'pdf', 'balance_sheet', date('YmdHis'));
		} else {
			$this->template = 'report/balance_sheet_print.tpl';
			$this->response->setOutput($this->render());
		}
	}

	private function buildStatements($date_end) {
		$sections = array(
			'activo' => array(),
			'pasivo' => array()
		);

		$totals = array(
			'activo' => 0,
			'pasivo' => 0
		);

		foreach ($this->model_report_balance_sheet->getBalances($date_end) as $account) {
			$classification = $this->classify($account['code']);

			if (!$classification) {
				continue;
			}

			list($statement, $section_code, $section_label, $section_order, $group_code, $group_label, $group_order, $nature) = $classification;

			$debit  = (float)$account['debit'];
			$credit = (float)$account['credit'];

			if ($nature == 'credit') {
				$amount = $credit - $debit;
			} else {
				$amount = $debit - $credit;
			}

			if ($amount == 0.0) {
				continue;
			}

			if (!isset($sections[$statement][$section_code])) {
				$sections[$statement][$section_code] = array(
					'label' => $section_label,
					'order' => $section_order,
					'total' => 0,
					'groups' => array()
				);
			}

			if (!isset($sections[$statement][$section_code]['groups'][$group_code])) {
				$sections[$statement][$section_code]['groups'][$group_code] = array(
					'label' => $group_label,
					'order' => $group_order,
					'total' => 0,
					'rows' => array()
				);
			}

			$sections[$statement][$section_code]['groups'][$group_code]['rows'][] = array(
				'code'   => $account['code'],
				'title'  => $account['title'],
				'amount' => $this->formatAmount($amount)
			);

			$sections[$statement][$section_code]['groups'][$group_code]['total'] += $amount;
			$sections[$statement][$section_code]['total']                       += $amount;
			$totals[$statement]                                                 += $amount;
		}

		$statements = array();

		$statements[] = $this->formatStatement($this->language->get('text_activo'), $sections['activo'], $totals['activo']);
		$statements[] = $this->formatStatement($this->language->get('text_patrimonio_pasivo'), $sections['pasivo'], $totals['pasivo']);

		return $statements;
	}

	private function formatStatement($title, $sections, $total) {
		uasort($sections, function ($a, $b) {
			return $a['order'] - $b['order'];
		});

		$formatted_sections = array();

		foreach ($sections as $section) {
			uasort($section['groups'], function ($a, $b) {
				return $a['order'] - $b['order'];
			});

			$formatted_groups = array();

			foreach ($section['groups'] as $group) {
				$formatted_groups[] = array(
					'label' => $group['label'],
					'total' => $this->formatAmount($group['total']),
					'rows'  => $group['rows']
				);
			}

			$formatted_sections[] = array(
				'label'  => $section['label'],
				'total'  => $this->formatAmount($section['total']),
				'groups' => $formatted_groups
			);
		}

		return array(
			'title'    => $title,
			'sections' => $formatted_sections,
			'total'    => $this->formatAmount($total)
		);
	}

	private function classify($code) {
		$p = (int)substr($code, 0, 3);

		// ACTIVO NO CORRIENTE
		if (($p >= 200 && $p <= 219) || $p == 281 || $p == 291) {
			return array('activo', 'A', 'A) Activo no corriente', 10, 'I', 'I. Inmovilizado intangible', 10, 'debit');
		}
		if (($p >= 220 && $p <= 239) || $p == 282 || $p == 292) {
			return array('activo', 'A', 'A) Activo no corriente', 10, 'II', 'II. Inmovilizado material', 20, 'debit');
		}
		if (($p >= 240 && $p <= 269) || ($p >= 293 && $p <= 298)) {
			return array('activo', 'A', 'A) Activo no corriente', 10, 'III', 'III. Inversiones financieras a largo plazo', 30, 'debit');
		}
		if (($p >= 270 && $p <= 272) || $p == 474) {
			return array('activo', 'A', 'A) Activo no corriente', 10, 'IV', 'IV. Otros activos no corrientes', 40, 'debit');
		}

		// ACTIVO CORRIENTE
		if ($p >= 300 && $p <= 399) {
			return array('activo', 'B', 'B) Activo corriente', 20, 'I', 'I. Existencias', 10, 'debit');
		}
		if (($p >= 430 && $p <= 449) || $p == 460 || $p == 470 || $p == 471 || $p == 472 || $p == 490 || $p == 493 || $p == 494 || $p == 499) {
			return array('activo', 'B', 'B) Activo corriente', 20, 'II', 'II. Deudores comerciales y otras cuentas a cobrar', 20, 'debit');
		}
		if (($p >= 530 && $p <= 549) || in_array($p, array(555, 556, 558, 565, 566)) || ($p >= 593 && $p <= 598)) {
			return array('activo', 'B', 'B) Activo corriente', 20, 'III', 'III. Inversiones financieras a corto plazo', 30, 'debit');
		}
		if ($p == 480 || $p == 580) {
			return array('activo', 'B', 'B) Activo corriente', 20, 'IV', 'IV. Periodificaciones a corto plazo', 40, 'debit');
		}
		if ($p >= 570 && $p <= 575) {
			return array('activo', 'B', 'B) Activo corriente', 20, 'V', 'V. Efectivo y otros activos liquidos equivalentes', 50, 'debit');
		}

		// PATRIMONIO NETO
		if ($p >= 190 && $p <= 199) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'A0', '(Desembolsos no exigidos)', 5, 'debit');
		}
		if ($p >= 100 && $p <= 103) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'I', 'I. Capital', 10, 'credit');
		}
		if ($p == 110) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'II', 'II. Prima de emision', 20, 'credit');
		}
		if ($p >= 111 && $p <= 119) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'III', 'III. Reservas', 30, 'credit');
		}
		if ($p == 120 || $p == 121 || $p == 122) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'IV', 'IV. Resultados de ejercicios anteriores', 40, 'credit');
		}
		if ($p == 129) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'V', 'V. Resultado del ejercicio', 50, 'credit');
		}
		if ($p == 130 || $p == 131) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'VI', 'VI. Subvenciones, donaciones y legados recibidos', 60, 'credit');
		}
		if ($p == 135 || $p == 136) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'VII', 'VII. Ajustes por cambios de valor', 70, 'credit');
		}
		if ($p == 557) {
			return array('pasivo', 'A', 'A) Patrimonio neto', 10, 'VIII', '(Dividendo a cuenta)', 80, 'debit');
		}

		// PASIVO NO CORRIENTE
		if ($p >= 140 && $p <= 144) {
			return array('pasivo', 'B', 'B) Pasivo no corriente', 20, 'I', 'I. Provisiones a largo plazo', 10, 'credit');
		}
		if ($p == 160 || $p == 161) {
			return array('pasivo', 'B', 'B) Pasivo no corriente', 20, 'III', 'III. Deudas con empresas del grupo y asociadas a largo plazo', 30, 'credit');
		}
		if (($p >= 150 && $p <= 155) || ($p >= 162 && $p <= 165) || ($p >= 170 && $p <= 174) || $p == 180 || $p == 185) {
			return array('pasivo', 'B', 'B) Pasivo no corriente', 20, 'II', 'II. Deudas a largo plazo', 20, 'credit');
		}

		// PASIVO CORRIENTE
		if (($p >= 500 && $p <= 509) || ($p >= 520 && $p <= 527) || $p == 560 || $p == 561) {
			return array('pasivo', 'C', 'C) Pasivo corriente', 30, 'II', 'II. Deudas a corto plazo', 20, 'credit');
		}
		if (($p >= 510 && $p <= 517) || ($p >= 550 && $p <= 553)) {
			return array('pasivo', 'C', 'C) Pasivo corriente', 30, 'III', 'III. Deudas con empresas del grupo y asociadas a corto plazo', 30, 'credit');
		}
		if (($p >= 400 && $p <= 411) || $p == 419 || $p == 465 || ($p >= 475 && $p <= 477) || $p == 479) {
			return array('pasivo', 'C', 'C) Pasivo corriente', 30, 'V', 'V. Acreedores comerciales y otras cuentas a pagar', 50, 'credit');
		}
		if ($p == 485 || $p == 585) {
			return array('pasivo', 'C', 'C) Pasivo corriente', 30, 'VI', 'VI. Periodificaciones a corto plazo', 60, 'credit');
		}

		return null;
	}

	private function formatAmount($value) {
		return number_format((float)$value, 2, ',', '.');
	}

	// El datepicker (clase .date de common.js) envia DD-MM-YYYY; el modelo compara en formato YYYY-MM-DD.
	private function toSqlDate($date) {
		if (empty($date) || !preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $date, $match)) {
			return '';
		}

		return $match[3] . '-' . $match[2] . '-' . $match[1];
	}

	private function buildUrl() {
		$url = '';

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		return $url;
	}
}

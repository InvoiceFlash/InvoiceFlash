<?php
class ControllerReportMod111 extends Controller {
	// Cuenta donde se registran las retenciones de IRPF practicadas a proveedores
	// (Hacienda Publica, acreedor por retenciones practicadas) y tipo de retencion
	// usado para estimar la base imponible (base = retencion / tipo), ya que los
	// asientos de accounting/entry no guardan ni proveedor ni base por separado.
	const RETENTION_ACCOUNT = '4751000000';
	const RETENTION_RATE    = 0.15;

	public function index() {
		$this->language->load('report/mod111');

		$this->document->setTitle($this->language->get('heading_title'));

		$filter_year     = isset($this->request->get['filter_year']) ? (int)$this->request->get['filter_year'] : (int)date('Y');
		$filter_quarter  = isset($this->request->get['filter_quarter']) ? (int)$this->request->get['filter_quarter'] : $this->currentQuarter();
		$filter_casilla_29 = isset($this->request->get['filter_casilla_29']) ? (float)str_replace(',', '.', $this->request->get['filter_casilla_29']) : 0;

		if ($filter_quarter < 1 || $filter_quarter > 4) {
			$filter_quarter = 1;
		}

		$url = $this->buildUrl();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/mod111', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/mod111');

		$this->data['result'] = $this->buildResult($filter_year, $filter_quarter, $filter_casilla_29);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_period']         = $this->language->get('text_period');
		$this->data['text_quarter']        = $this->language->get('text_quarter');
		$this->data['text_disclaimer']     = $this->language->get('text_disclaimer');
		$this->data['text_casilla_07']     = $this->language->get('text_casilla_07');
		$this->data['text_casilla_08']     = $this->language->get('text_casilla_08');
		$this->data['text_casilla_09']     = $this->language->get('text_casilla_09');
		$this->data['text_casilla_28']     = $this->language->get('text_casilla_28');
		$this->data['text_casilla_29']     = $this->language->get('text_casilla_29');
		$this->data['text_casilla_30']     = $this->language->get('text_casilla_30');
		$this->data['text_estimated']      = $this->language->get('text_estimated');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');

		$this->data['quarters'] = array(
			1 => $this->language->get('text_q1'),
			2 => $this->language->get('text_q2'),
			3 => $this->language->get('text_q3'),
			4 => $this->language->get('text_q4')
		);

		$this->data['filter_year']       = $filter_year;
		$this->data['filter_quarter']    = $filter_quarter;
		$this->data['filter_casilla_29'] = $filter_casilla_29;

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'report/mod111_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->language->load('report/mod111');

		if (!$this->user->hasPermission('access', 'report/mod111')) {
			die('Permission denied');
		}

		$filter_year       = isset($this->request->get['filter_year']) ? (int)$this->request->get['filter_year'] : (int)date('Y');
		$filter_quarter    = isset($this->request->get['filter_quarter']) ? (int)$this->request->get['filter_quarter'] : $this->currentQuarter();
		$filter_casilla_29 = isset($this->request->get['filter_casilla_29']) ? (float)str_replace(',', '.', $this->request->get['filter_casilla_29']) : 0;

		$this->load->model('report/mod111');

		$result = $this->buildResult($filter_year, $filter_quarter, $filter_casilla_29);

		$csv  = "\xEF\xBB\xBF"; // UTF-8 BOM para Excel
		$csv .= '"Casilla";"Concepto";"Importe"' . "\n";
		$csv .= '"07";"Rendimientos de actividades economicas - Nº perceptores (estimado)";"' . $result['casilla_07'] . '"' . "\n";
		$csv .= '"08";"Rendimientos de actividades economicas - Base (estimada, tipo ' . ($result['rate'] * 100) . '%)";"' . $result['casilla_08'] . '"' . "\n";
		$csv .= '"09";"Rendimientos de actividades economicas - Retenciones";"' . $result['casilla_09'] . '"' . "\n";
		$csv .= '"28";"Suma de retenciones e ingresos a cuenta";"' . $result['casilla_28'] . '"' . "\n";
		$csv .= '"29";"A compensar";"' . $result['casilla_29'] . '"' . "\n";
		$csv .= '"30";"Resultado";"' . $result['casilla_30'] . '"' . "\n";

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="modelo111_' . $filter_year . '_' . $filter_quarter . 'T.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
	}

	private function buildResult($year, $quarter, $casilla_29) {
		list($date_start, $date_end) = $this->quarterRange($year, $quarter);

		$summary = $this->model_report_mod111->getRetentionSummary(self::RETENTION_ACCOUNT, $date_start, $date_end);

		$retencion = (float)$summary['credit'] - (float)$summary['debit'];
		$base      = self::RETENTION_RATE > 0 ? $retencion / self::RETENTION_RATE : 0;

		$casilla_28 = $retencion;
		$casilla_30 = $casilla_28 - $casilla_29;

		return array(
			'date_start'  => $date_start,
			'date_end'    => $date_end,
			'rate'        => self::RETENTION_RATE,
			'casilla_07'  => (int)$summary['entries'],
			'casilla_08'  => $this->formatAmount($base),
			'casilla_09'  => $this->formatAmount($retencion),
			'casilla_28'  => $this->formatAmount($casilla_28),
			'casilla_29'  => $this->formatAmount($casilla_29),
			'casilla_30'  => $this->formatAmount($casilla_30)
		);
	}

	private function quarterRange($year, $quarter) {
		$starts = array(1 => '01-01', 2 => '01-04', 3 => '01-07', 4 => '01-10');
		$ends   = array(1 => '31-03', 2 => '30-06', 3 => '30-09', 4 => '31-12');

		$date_start = $year . '-' . substr($starts[$quarter], 3, 2) . '-' . substr($starts[$quarter], 0, 2);
		$date_end   = $year . '-' . substr($ends[$quarter], 3, 2) . '-' . substr($ends[$quarter], 0, 2);

		return array($date_start, $date_end);
	}

	private function currentQuarter() {
		return (int)ceil((int)date('n') / 3);
	}

	private function formatAmount($value) {
		return number_format((float)$value, 2, ',', '.');
	}

	private function buildUrl() {
		$url = '';

		foreach (array('filter_year', 'filter_quarter', 'filter_casilla_29') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
			}
		}

		return $url;
	}
}

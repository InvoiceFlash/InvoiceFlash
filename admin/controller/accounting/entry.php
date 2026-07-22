<?php
class ControllerAccountingEntry extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('accounting/entry');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('accounting/entry', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_date']    = $this->language->get('entry_date');
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_concept'] = $this->language->get('entry_concept');
		$this->data['entry_debit']   = $this->language->get('entry_debit');
		$this->data['entry_credit']  = $this->language->get('entry_credit');

		$this->data['column_account'] = $this->language->get('column_account');
		$this->data['column_concept'] = $this->language->get('column_concept');
		$this->data['column_debit']   = $this->language->get('column_debit');
		$this->data['column_credit']  = $this->language->get('column_credit');

		$this->data['text_total']       = $this->language->get('text_total');
		$this->data['text_new_tooltip'] = $this->language->get('text_new_tooltip');

		$this->data['button_new']  = $this->language->get('button_new');
		$this->data['button_save'] = $this->language->get('button_save');

		$conta_digits = (int)$this->config->get('config_conta_digits') ?: 10;

		$this->data['conta_digits'] = $conta_digits;

		$this->data['error_account_js']    = sprintf($this->language->get('error_account'), $conta_digits);
		$this->data['error_unbalanced_js'] = $this->language->get('error_unbalanced');
		$this->data['error_no_lines_js']   = $this->language->get('error_no_lines');
		$this->data['error_both_js']       = $this->language->get('error_debit_credit_both');

		$this->data['save']  = $this->url->link('accounting/entry/save', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->session->data['entry_error'])) {
			$this->data['error_warning'] = $this->session->data['entry_error'];

			unset($this->session->data['entry_error']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->template = 'accounting/entry_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function save() {
		$this->language->load('accounting/entry');

		$this->load->model('accounting/entry');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$accounts = $this->request->post['account'];
			$concepts = $this->request->post['concept'];
			$debits   = $this->request->post['debit'];
			$credits  = $this->request->post['credit'];

			$lines = array();

			foreach ($accounts as $i => $account) {
				$lines[] = array(
					'account' => $account,
					'concept' => $concepts[$i],
					'debit'   => (float)str_replace(',', '.', $debits[$i]),
					'credit'  => (float)str_replace(',', '.', $credits[$i])
				);
			}

			$entry_id = $this->model_accounting_entry->getNextEntryId();
			$line_date = $this->toSqlDate($this->request->post['line_date']);

			$this->model_accounting_entry->addEntry($entry_id, $line_date, $lines, array(
				'user_id'  => $this->user->getId(),
				'username' => $this->user->getUserName()
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('accounting/entry', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		if (isset($this->error['warning'])) {
			$this->session->data['entry_error'] = $this->error['warning'];
		}

		$this->redirect($this->url->link('accounting/entry', 'token=' . $this->session->data['token'], 'SSL'));
	}

	// El datepicker (clase .date de common.js) envia DD-MM-YYYY; guardamos en formato YYYY-MM-DD.
	private function toSqlDate($date) {
		if (empty($date) || !preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $date, $match)) {
			return date('Y-m-d');
		}

		return $match[3] . '-' . $match[2] . '-' . $match[1];
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'accounting/entry')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error && (empty($this->request->post['account']) || !is_array($this->request->post['account']))) {
			$this->error['warning'] = $this->language->get('error_no_lines');
		}

		if (!$this->error) {
			$conta_digits = (int)$this->config->get('config_conta_digits') ?: 10;

			$total_debit  = 0;
			$total_credit = 0;

			foreach ($this->request->post['account'] as $i => $account) {
				if (!preg_match('/^[0-9]+$/', $account) || utf8_strlen($account) != $conta_digits) {
					$this->error['warning'] = sprintf($this->language->get('error_account'), $conta_digits);
					break;
				}

				$debit  = (float)str_replace(',', '.', $this->request->post['debit'][$i]);
				$credit = (float)str_replace(',', '.', $this->request->post['credit'][$i]);

				$total_debit  += $debit;
				$total_credit += $credit;
			}

			if (!$this->error && (round($total_debit, 2) != round($total_credit, 2))) {
				$this->error['warning'] = $this->language->get('error_unbalanced');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}

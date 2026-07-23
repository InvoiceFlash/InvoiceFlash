<?php
class ControllerAccountingRegularization extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('accounting/regularization');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('accounting/regularization', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_date']    = $this->language->get('entry_date');
		$this->data['entry_concept'] = $this->language->get('entry_concept');

		$this->data['button_create'] = $this->language->get('button_create');
		$this->data['button_exit']   = $this->language->get('button_exit');

		$this->data['default_concept'] = $this->language->get('text_default_concept');

		$this->data['save'] = $this->url->link('accounting/regularization/save', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['exit'] = $this->url->link('accounting/review', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->session->data['regularization_error'])) {
			$this->data['error_warning'] = $this->session->data['regularization_error'];

			unset($this->session->data['regularization_error']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->template = 'accounting/regularization_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function save() {
		$this->language->load('accounting/regularization');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->load->model('accounting/regularization');
			$this->load->model('accounting/entry');

			$date        = $this->toSqlDate($this->request->post['line_date']);
			$concept     = trim($this->request->post['concept']);
			$result_account = $this->config->get('config_conta_result_account');

			$accounts = $this->model_accounting_regularization->getAccountsToClose($date);

			$lines        = array();
			$total_debit  = 0;
			$total_credit = 0;

			foreach ($accounts as $row) {
				$net = round((float)$row['total_debit'] - (float)$row['total_credit'], 2);

				if ($net > 0) {
					$lines[] = array(
						'account' => $row['account'],
						'concept' => $concept,
						'debit'   => 0,
						'credit'  => $net
					);

					$total_debit += $net;
				} elseif ($net < 0) {
					$lines[] = array(
						'account' => $row['account'],
						'concept' => $concept,
						'debit'   => -$net,
						'credit'  => 0
					);

					$total_credit += -$net;
				}
			}

			if ($total_debit > 0) {
				$lines[] = array(
					'account' => $result_account,
					'concept' => $concept,
					'debit'   => round($total_debit, 2),
					'credit'  => 0
				);
			}

			if ($total_credit > 0) {
				$lines[] = array(
					'account' => $result_account,
					'concept' => $concept,
					'debit'   => 0,
					'credit'  => round($total_credit, 2)
				);
			}

			$entry_id = $this->model_accounting_entry->getNextEntryId();

			$this->model_accounting_entry->addEntry($entry_id, $date, $lines, array(
				'user_id'  => $this->user->getId(),
				'username' => $this->user->getUserName()
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('accounting/regularization', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		if (isset($this->error['warning'])) {
			$this->session->data['regularization_error'] = $this->error['warning'];
		}

		$this->redirect($this->url->link('accounting/regularization', 'token=' . $this->session->data['token'], 'SSL'));
	}

	// El datepicker (clase .date de common.js) envia DD-MM-YYYY; guardamos en formato YYYY-MM-DD.
	private function toSqlDate($date) {
		if (empty($date) || !preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $date, $match)) {
			return date('Y-m-d');
		}

		return $match[3] . '-' . $match[2] . '-' . $match[1];
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'accounting/regularization')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$concept = isset($this->request->post['concept']) ? trim($this->request->post['concept']) : '';

		if (!$this->error && ($concept === '')) {
			$this->error['warning'] = $this->language->get('error_concept');
		}

		if (!$this->error && !$this->config->get('config_conta_result_account')) {
			$this->error['warning'] = $this->language->get('error_no_result_account');
		}

		if (!$this->error) {
			$this->load->model('accounting/regularization');

			$date     = $this->toSqlDate($this->request->post['line_date']);
			$accounts = $this->model_accounting_regularization->getAccountsToClose($date);

			if (!$accounts) {
				$this->error['warning'] = $this->language->get('error_nothing_to_regularize');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}

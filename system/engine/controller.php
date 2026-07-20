<?php
abstract class Controller {
	protected $registry;	
	protected $id;
	protected $layout;
	protected $template;
	protected $children = array();
	protected $data = array();
	protected $output;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

	protected function forward($route, $args = array()) {
		return new Action($route, $args);
	}

	protected function redirect($url, $status = 302) {
		header('Status: ' . $status);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
		exit();				
	}

	protected function getChild($child, $args = array()) {
		$action = new Action($child, $args);

		if (file_exists($action->getFile())) {
			require_once($action->getFile());

			$class = $action->getClass();

			$controller = new $class($this->registry);

			$controller->{$action->getMethod()}($action->getArgs());

			return $controller->output;
		} else {
			trigger_error('Error: Could not load controller ' . $child . '!');
			exit();					
		}		
	}

	// Compares an "old" record (associative array, e.g. from a getX() model call
	// made before saving) against the newly posted data and returns only the
	// scalar fields that actually changed, split into 'original' and 'changed'.
	// Used to feed tool/user_logs' original/cambiado columns on edit actions.
	protected function diffFields($old, $new, $skip = array()) {
		$skip = array_merge(array('token', 'date_added', 'date_modified'), $skip);

		$original = array();
		$changed  = array();

		foreach ($new as $key => $new_value) {
			if (in_array($key, $skip) || is_array($new_value) || !array_key_exists($key, $old) || is_array($old[$key])) {
				continue;
			}

			$old_value = trim((string)$old[$key]);
			$new_value = trim((string)$new_value);

			if ($old_value === $new_value) {
				continue;
			}

			// '0' and '' both mean "not set" for id-like fields (country_id,
			// zone_id, etc.) - posting an empty value for a field that was
			// already unset isn't a real change.
			$old_unset = ($old_value === '' || $old_value === '0');
			$new_unset = ($new_value === '' || $new_value === '0');

			if ($old_unset && $new_unset) {
				continue;
			}

			$original[$key] = $old_value;
			$changed[$key]  = $new_value;
		}

		return array('original' => $original, 'changed' => $changed);
	}

	protected function hasAction($child, $args = array()) {
		$action = new Action($child, $args);

		if (file_exists($action->getFile())) {
			require_once($action->getFile());

			$class = $action->getClass();

			$controller = new $class($this->registry);

			if(method_exists($controller, $action->getMethod())){
				return true;
			}else{
				return false;
			}
		} else {
			return false;				
		}		
	}

	protected function render() {
		foreach ($this->children as $child) {
			$this->data[basename($child)] = $this->getChild($child);
		}

		if (file_exists(DIR_TEMPLATE . $this->template)) {
			extract($this->data);

			ob_start();

			require(DIR_TEMPLATE . $this->template);

			$this->output = ob_get_contents();

			ob_end_clean();

			return $this->output;
		} else {
			trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
			exit();				
		}
	}

	//add
	protected function renderPDF($template, $output, $name, $id, $watermark = '') {
		require_once(DIR_SYSTEM.'external/tcpdf/tcpdf.php');

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->AddPage();

		$this->template = $template;

		// output the HTML content
		$pdf->writeHTML($this->render(), true, false, true, false, '');

		if ($watermark) {
			$page_width = $pdf->getPageWidth();
			$page_height = $pdf->getPageHeight();

			$pdf->SetAlpha(0.2);
			$pdf->SetFont('helvetica', 'BI', 70);
			$pdf->SetTextColor(150, 150, 150);
			$pdf->StartTransform();
			$pdf->Rotate(45, $page_width / 2, $page_height / 2);
			$pdf->SetXY(0, ($page_height / 2) - 15);
			$pdf->Cell($page_width, 30, $watermark, 0, 0, 'C');
			$pdf->StopTransform();
			$pdf->SetAlpha(1);
		}

		if ($output == 'email'){
		    //$this->output =  $pdf->Output(DIR_DOWNLOAD . $name . '.pdf', 'F');
			$this->output =  $pdf->Output(DIR_DOWNLOAD . $name . '_' . $id . '.pdf', 'F');
		}else{		 
		    //$this->output =  $pdf->Output('invoice.pdf', 'I');
			$this->output =  $pdf->Output(DIR_DOWNLOAD . $name . '_' . $id . '.pdf', 'I');
		}
	}
	
	// Returns null on success or the error message on failure. Mail::send()
	// throws on SMTP/sendmail failure (bad credentials, unreachable host,
	// etc.) - left uncaught, that became an uncaught fatal error, which
	// callers invoke over AJAX with dataType 'json', so the PHP error page
	// broke JSON.parse on the client instead of surfacing a real message.
	protected function sendnewmail($to,$subject,$text,$lcFile) {
		$mail = new Mail();

        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($to);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_title'));
        $mail->setSubject($subject);

		$mail->setHTML(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));

        if(file_exists($lcFile)){
			$mail->addAttachment($lcFile);
		}

		$log=new Log('mail.log'); $log->write($mail);

		try {
			$mail->send();
		} catch (Exception $e) {
			$log->write($e->getMessage());

			return $e->getMessage();
		}

		return null;
    }
	//end
}
?>
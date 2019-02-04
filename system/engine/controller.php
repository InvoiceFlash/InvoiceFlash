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
	protected function renderPDF($template, $output, $name, $id) {
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

		if ($output == 'email'){	 
		    //$this->output =  $pdf->Output(DIR_DOWNLOAD . $name . '.pdf', 'F');
			$this->output =  $pdf->Output(DIR_DOWNLOAD . $name . '_' . $id . '.pdf', 'F');
		}else{		 
		    //$this->output =  $pdf->Output('invoice.pdf', 'I');
			$this->output =  $pdf->Output(DIR_DOWNLOAD . $name . '_' . $id . '.pdf', 'I');
		}
	}
	
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
 
        $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
        $mail->addAttachment($lcFile);
        $mail->send();
    }
	//end
}
?>
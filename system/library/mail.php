<?php
class Mail {
	protected $to;
	protected $from;
	protected $sender;
	protected $subject;
	protected $text;
	protected $html;
	protected $attachments = array();
	public $protocol = 'mail';
	public $hostname;
	public $username;
	public $password;
	public $port = 25;
	public $timeout = 5;
	public $newline = "\n";
	public $crlf = "\r\n";
	public $verp = false;
	public $parameter = '';

	public function setTo($to) {
		$this->to = $to;
	}

	public function setFrom($from) {
		$this->from = $from;
	}

	public function setSender($sender) {
		$this->sender = $sender;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function setHtml($html) {
		$this->html = $html;
	}

	public function addAttachment($filename) {
		$this->attachments[] = $filename;
	}

	public function send() {
		if (!$this->to) {
			throw new RuntimeException('Error: E-Mail to required!');
		}

		if (!$this->from) {
			throw new RuntimeException('Error: E-Mail from required!');
		}

		if (!$this->sender) {
			throw new RuntimeException('Error: E-Mail sender required!');
		}

		if (!$this->subject) {
			throw new RuntimeException('Error: E-Mail subject required!');
		}

		if (!$this->text && !$this->html) {
			throw new RuntimeException('Error: E-Mail message required!');
		}

		if (is_array($this->to)) {
			$to = implode(',', $this->to);
		} else {
			$to = $this->to;
		}

		// Stronger than md5(time()) - won't collide even when several mails
		// are built within the same second.
		$boundary = '----=_NextPart_' . md5(uniqid((string)mt_rand(), true));

		$encoded_subject = $this->encodeHeaderValue($this->subject);
		$encoded_sender  = $this->encodeHeaderValue($this->sender);

		$header = '';

		$header .= 'MIME-Version: 1.0' . $this->newline;

		if ($this->protocol != 'mail') {
			$header .= 'To: ' . $to . $this->newline;
			// RFC 2047 encode the subject for SMTP too, the previous version
			// only encoded it for the mail() protocol and sent raw UTF-8
			// otherwise, which some mail servers mangle.
			$header .= 'Subject: ' . $encoded_subject . $this->newline;
		}

		$header .= 'Date: ' . date('D, d M Y H:i:s O') . $this->newline;
		$header .= 'From: ' . $encoded_sender . ' <' . $this->from . '>' . $this->newline;
		$header .= 'Reply-To: ' . $encoded_sender . ' <' . $this->from . '>' . $this->newline;
		$header .= 'Return-Path: ' . $this->from . $this->newline;
		$header .= 'X-Mailer: PHP/' . phpversion() . $this->newline;
		$header .= 'Content-Type: multipart/related; boundary="' . $boundary . '"' . $this->newline . $this->newline;

		if (!$this->html) {
			$message  = '--' . $boundary . $this->newline;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;
			$message .= $this->text . $this->newline;
		} else {
			$message  = '--' . $boundary . $this->newline;
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . $this->newline . $this->newline;
			$message .= '--' . $boundary . '_alt' . $this->newline;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;

			if ($this->text) {
				$message .= $this->text . $this->newline;
			} else {
				$message .= 'This is a HTML email and your email client software does not support HTML email!' . $this->newline;
			}

			$message .= '--' . $boundary . '_alt' . $this->newline;
			$message .= 'Content-Type: text/html; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;
			$message .= $this->html . $this->newline;
			$message .= '--' . $boundary . '_alt--' . $this->newline;
		}

		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
				$handle = fopen($attachment, 'r');

				$content = fread($handle, filesize($attachment));

				fclose($handle);

				$message .= '--' . $boundary . $this->newline;
				$message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . $this->newline;
				$message .= 'Content-Transfer-Encoding: base64' . $this->newline;
				$message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . $this->newline;
				$message .= 'Content-ID: <' . basename(urlencode($attachment)) . '>' . $this->newline;
				$message .= 'X-Attachment-Id: ' . basename(urlencode($attachment)) . $this->newline . $this->newline;
				$message .= chunk_split(base64_encode($content));
			}
		}

		$message .= '--' . $boundary . '--' . $this->newline;

		if ($this->protocol == 'mail') {
			$this->sendViaMailFunction($to, $encoded_subject, $message, $header);
		} elseif ($this->protocol == 'smtp') {
			$this->sendViaSmtp($message, $header);
		}
	}

	/**
	 * RFC 2047 "encoded word", used for the From/Reply-To display name and
	 * (now consistently, see send()) the Subject header.
	 */
	protected function encodeHeaderValue($value) {
		return '=?UTF-8?B?' . base64_encode($value) . '?=';
	}

	protected function sendViaMailFunction($to, $subject, $message, $header) {
		ini_set('sendmail_from', $this->from);

		if ($this->parameter) {
			mail($to, $subject, $message, $header, $this->parameter);
		} else {
			mail($to, $subject, $message, $header);
		}
	}

	protected function sendViaSmtp($message, $header) {
		$hostname = (string)$this->hostname;
		$encryption = '';

		// Keep supporting the existing 'tls://host' / 'ssl://host' convention
		// used in the admin SMTP settings, but actually act on it below -
		// previously STARTTLS was requested without ever upgrading the
		// connection, so SMTP AUTH credentials were sent unencrypted.
		if (stripos($hostname, 'ssl://') === 0) {
			$encryption = 'ssl';
			$hostname = substr($hostname, 6);
		} elseif (stripos($hostname, 'tls://') === 0) {
			$encryption = 'tls';
			$hostname = substr($hostname, 6);
		}

		// Implicit TLS (typically port 465): negotiate encryption as part of
		// the connection itself. Explicit STARTTLS (typically port 587):
		// connect in plain text first, upgrade further down.
		$transport = ($encryption == 'ssl') ? 'ssl://' . $hostname : $hostname;

		$handle = @fsockopen($transport, $this->port, $errno, $errstr, $this->timeout);

		if (!$handle) {
			throw new RuntimeException('Error: ' . $errstr . ' (' . $errno . ')');
		}

		stream_set_timeout($handle, $this->timeout);

		$this->smtpExpect($handle, array(220), 'Error: Server did not send a greeting!');

		if ($encryption == 'tls') {
			$this->smtpCommand($handle, 'STARTTLS');
			$this->smtpExpect($handle, array(220), 'Error: STARTTLS not accepted from server!');

			if (!stream_socket_enable_crypto($handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
				fclose($handle);

				throw new RuntimeException('Error: Unable to start TLS encryption!');
			}
		}

		if (!empty($this->username) && !empty($this->password)) {
			$this->smtpCommand($handle, 'EHLO ' . $this->getClientHostname());
			$this->smtpExpect($handle, array(250), 'Error: EHLO not accepted from server!');

			$this->smtpCommand($handle, 'AUTH LOGIN');
			$this->smtpExpect($handle, array(334), 'Error: AUTH LOGIN not accepted from server!');

			$this->smtpCommand($handle, base64_encode($this->username));
			$this->smtpExpect($handle, array(334), 'Error: Username not accepted from server!');

			$this->smtpCommand($handle, base64_encode($this->password));
			$this->smtpExpect($handle, array(235), 'Error: Password not accepted from server!');
		} else {
			$this->smtpCommand($handle, 'HELO ' . $this->getClientHostname());
			$this->smtpExpect($handle, array(250), 'Error: HELO not accepted from server!');
		}

		$this->smtpCommand($handle, 'MAIL FROM: <' . $this->from . '>' . ($this->verp ? 'XVERP' : ''));
		$this->smtpExpect($handle, array(250), 'Error: MAIL FROM not accepted from server!');

		foreach ((array)$this->to as $recipient) {
			$this->smtpCommand($handle, 'RCPT TO: <' . $recipient . '>');
			$this->smtpExpect($handle, array(250, 251), 'Error: RCPT TO not accepted from server!');
		}

		$this->smtpCommand($handle, 'DATA');
		$this->smtpExpect($handle, array(354), 'Error: DATA not accepted from server!');

		// According to rfc 821 we should not send more than 1000 including the CRLF
		$data = str_replace(array("\r\n", "\r"), "\n", $header . $message);

		foreach (explode("\n", $data) as $line) {
			foreach (str_split($line, 998) as $chunk) {
				fputs($handle, $chunk . $this->crlf);
			}
		}

		fputs($handle, '.' . $this->crlf);
		$this->smtpExpect($handle, array(250), 'Error: DATA not accepted from server!');

		$this->smtpCommand($handle, 'QUIT');
		$this->smtpExpect($handle, array(221), 'Error: QUIT not accepted from server!');

		fclose($handle);
	}

	protected function getClientHostname() {
		$server_name = getenv('SERVER_NAME');

		return $server_name ? $server_name : 'localhost';
	}

	protected function smtpCommand($handle, $command) {
		fputs($handle, $command . $this->crlf);
	}

	protected function smtpExpect($handle, $codes, $error) {
		$reply = '';

		while (($line = fgets($handle, 515)) !== false) {
			$reply .= $line;

			if (substr($line, 3, 1) == ' ') {
				break;
			}
		}

		if (!in_array((int)substr($reply, 0, 3), $codes)) {
			fclose($handle);

			throw new RuntimeException($error);
		}

		return $reply;
	}
}
?>

<?php
// Generador de ficheros de adeudos directos SEPA (pain.008.001.02, el que aceptan los bancos espanoles).
// Sin dependencias: solo DOM. No toca la base de datos; el controlador le pasa los datos ya validados.
class SepaSdd {
	const NS = 'urn:iso:std:iso:20022:tech:xsd:pain.008.001.02';

	// Juego de caracteres SEPA: letras, cifras y / - ? : ( ) . , ' + espacio. Traduce acentos y descarta el resto.
	public static function clean($text, $max) {
		$text = html_entity_decode((string)$text, ENT_QUOTES, 'UTF-8');

		$text = strtr($text, array(
			'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'ã' => 'a', 'å' => 'a', 'Á' => 'A', 'À' => 'A', 'Ä' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Å' => 'A',
			'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e', 'É' => 'E', 'È' => 'E', 'Ë' => 'E', 'Ê' => 'E',
			'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i', 'Í' => 'I', 'Ì' => 'I', 'Ï' => 'I', 'Î' => 'I',
			'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'õ' => 'o', 'Ó' => 'O', 'Ò' => 'O', 'Ö' => 'O', 'Ô' => 'O', 'Õ' => 'O',
			'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u', 'Ú' => 'U', 'Ù' => 'U', 'Ü' => 'U', 'Û' => 'U',
			'ñ' => 'n', 'Ñ' => 'N', 'ç' => 'c', 'Ç' => 'C', 'ß' => 'ss', 'æ' => 'ae', 'Æ' => 'AE', 'œ' => 'oe', 'Œ' => 'OE',
			'&' => ' y ', '_' => ' ', ';' => ',', '"' => ' ', '€' => 'EUR'
		));

		$text = preg_replace('/[^A-Za-z0-9\/\-\?:\(\)\.,\'\+ ]/', ' ', $text);
		$text = trim(preg_replace('/\s+/', ' ', $text));

		return (string)substr($text, 0, $max);
	}

	// Como clean() pero sin espacios, para identificadores (MsgId, EndToEndId...).
	public static function id($text, $max) {
		return (string)substr(preg_replace('/[^A-Za-z0-9\/\-\?:\(\)\.,\'\+]/', '', self::clean($text, 200)), 0, $max);
	}

	public static function normalizeIban($iban) {
		return strtoupper(preg_replace('/[\s\-]+/', '', (string)$iban));
	}

	// IBAN valido: forma + digitos de control (modulo 97).
	public static function ibanValid($iban) {
		$iban = self::normalizeIban($iban);

		if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{10,30}$/', $iban)) {
			return false;
		}

		$moved = substr($iban, 4) . substr($iban, 0, 4);
		$digits = '';

		foreach (str_split($moved) as $char) {
			$digits .= ctype_alpha($char) ? (string)(ord($char) - 55) : $char;
		}

		$rest = 0;

		foreach (str_split($digits, 7) as $chunk) {
			$rest = (int)($rest . $chunk) % 97;
		}

		return $rest === 1;
	}

	public static function bicValid($bic) {
		return (bool)preg_match('/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/', strtoupper(trim((string)$bic)));
	}

	public static function isTargetDay($ts) {
		return (int)date('N', $ts) < 6;
	}

	// Siguiente dia habil (L-V) estrictamente posterior a $ts. No conoce festivos TARGET2.
	public static function nextBusinessDay($ts) {
		do {
			$ts = strtotime('+1 day', $ts);
		} while (!self::isTargetDay($ts));

		return $ts;
	}

	// Fecha de cobro: la del vencimiento, pero no antes del dia habil siguiente (plazo D-1 de CORE y B2B) ni en fin de semana.
	public static function collectionDate($due, $now) {
		$min = self::nextBusinessDay(strtotime(date('Y-m-d', $now)));
		$ts = strtotime(substr((string)$due, 0, 10));

		if (!$ts || $ts < $min) {
			$ts = $min;
		}

		while (!self::isTargetDay($ts)) {
			$ts = strtotime('+1 day', $ts);
		}

		return date('Y-m-d', $ts);
	}

	private static function el($doc, $parent, $name, $text = null) {
		$node = $doc->createElement($name);

		if ($text !== null) {
			$node->appendChild($doc->createTextNode($text));
		}

		$parent->appendChild($node);

		return $node;
	}

	private static function agent($doc, $parent, $name, $bic) {
		$agent = self::el($doc, $parent, $name);
		$inst = self::el($doc, $agent, 'FinInstnId');

		if ($bic !== '') {
			self::el($doc, $inst, 'BIC', strtoupper($bic));
		} else {
			$other = self::el($doc, $inst, 'Othr');
			self::el($doc, $other, 'Id', 'NOTPROVIDED');
		}
	}

	private static function cents($amount) {
		return (int)round($amount * 100);
	}

	private static function money($cents) {
		return number_format($cents / 100, 2, '.', '');
	}

	/*
	 * $creditor: name, iban, bic, id (identificador de acreedor)
	 * $payments: lista de array(ref, amount, due, debtor_name, iban, bic, mandate_id, mandate_date, seq, info)
	 * $options:  scheme (CORE|B2B), msg_id, now (timestamp)
	 * Devuelve array(xml, count, total, groups => lista de array(seq, date, count, total))
	 */
	public static function build($creditor, $payments, $options) {
		$now = isset($options['now']) ? $options['now'] : time();
		$scheme = (isset($options['scheme']) && $options['scheme'] == 'CORE') ? 'CORE' : 'B2B';
		$msg_id = self::id(isset($options['msg_id']) ? $options['msg_id'] : 'SDD' . date('YmdHis', $now), 35);

		$cred_name = self::clean($creditor['name'], 70);
		$cred_name = $cred_name !== '' ? $cred_name : 'NOTPROVIDED';
		$cred_iban = self::normalizeIban($creditor['iban']);
		$cred_bic = strtoupper(trim((string)$creditor['bic']));
		$cred_bic = self::bicValid($cred_bic) ? $cred_bic : '';
		$cred_id = self::id($creditor['id'], 35);

		$groups = array();

		foreach ($payments as $payment) {
			$date = self::collectionDate($payment['due'], $now);
			$key = $payment['seq'] . '|' . $date;

			if (!isset($groups[$key])) {
				$groups[$key] = array('seq' => $payment['seq'], 'date' => $date, 'payments' => array());
			}

			$groups[$key]['payments'][] = $payment;
		}

		ksort($groups);

		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->formatOutput = true;

		$root = $doc->createElementNS(self::NS, 'Document');
		$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', self::NS . ' pain.008.001.02.xsd');
		$doc->appendChild($root);

		$init = self::el($doc, $root, 'CstmrDrctDbtInitn');

		$total_count = 0;
		$total_cents = 0;

		foreach ($groups as $group) {
			foreach ($group['payments'] as $payment) {
				$total_count++;
				$total_cents += self::cents($payment['amount']);
			}
		}

		$header = self::el($doc, $init, 'GrpHdr');
		self::el($doc, $header, 'MsgId', $msg_id);
		self::el($doc, $header, 'CreDtTm', date('Y-m-d\TH:i:s', $now));
		self::el($doc, $header, 'NbOfTxs', (string)$total_count);
		self::el($doc, $header, 'CtrlSum', self::money($total_cents));
		$initiating = self::el($doc, $header, 'InitgPty');
		self::el($doc, $initiating, 'Nm', $cred_name);
		$initiating_id = self::el($doc, self::el($doc, self::el($doc, $initiating, 'Id'), 'OrgId'), 'Othr');
		self::el($doc, $initiating_id, 'Id', $cred_id);

		$summary = array();
		$index = 0;

		foreach ($groups as $group) {
			$index++;

			$group_cents = 0;

			foreach ($group['payments'] as $payment) {
				$group_cents += self::cents($payment['amount']);
			}

			$info = self::el($doc, $init, 'PmtInf');
			self::el($doc, $info, 'PmtInfId', self::id($msg_id . '-' . $index, 35));
			self::el($doc, $info, 'PmtMtd', 'DD');
			self::el($doc, $info, 'BtchBookg', 'true');
			self::el($doc, $info, 'NbOfTxs', (string)count($group['payments']));
			self::el($doc, $info, 'CtrlSum', self::money($group_cents));

			$type = self::el($doc, $info, 'PmtTpInf');
			self::el($doc, self::el($doc, $type, 'SvcLvl'), 'Cd', 'SEPA');
			self::el($doc, self::el($doc, $type, 'LclInstrm'), 'Cd', $scheme);
			self::el($doc, $type, 'SeqTp', $group['seq']);

			self::el($doc, $info, 'ReqdColltnDt', $group['date']);
			self::el($doc, self::el($doc, $info, 'Cdtr'), 'Nm', $cred_name);
			self::el($doc, self::el($doc, self::el($doc, $info, 'CdtrAcct'), 'Id'), 'IBAN', $cred_iban);
			self::agent($doc, $info, 'CdtrAgt', $cred_bic);
			self::el($doc, $info, 'ChrgBr', 'SLEV');

			$scheme_id = self::el($doc, self::el($doc, self::el($doc, $info, 'CdtrSchmeId'), 'Id'), 'PrvtId');
			$other = self::el($doc, $scheme_id, 'Othr');
			self::el($doc, $other, 'Id', $cred_id);
			self::el($doc, self::el($doc, $other, 'SchmeNm'), 'Prtry', 'SEPA');

			foreach ($group['payments'] as $payment) {
				$tx = self::el($doc, $info, 'DrctDbtTxInf');

				self::el($doc, self::el($doc, $tx, 'PmtId'), 'EndToEndId', self::id($payment['ref'], 35) ?: 'NOTPROVIDED');

				$amount = self::el($doc, $tx, 'InstdAmt', self::money(self::cents($payment['amount'])));
				$amount->setAttribute('Ccy', 'EUR');

				$mandate = self::el($doc, self::el($doc, $tx, 'DrctDbtTx'), 'MndtRltdInf');
				self::el($doc, $mandate, 'MndtId', self::id($payment['mandate_id'], 35));
				self::el($doc, $mandate, 'DtOfSgntr', $payment['mandate_date']);

				$debtor_bic = strtoupper(trim((string)$payment['bic']));
				self::agent($doc, $tx, 'DbtrAgt', self::bicValid($debtor_bic) ? $debtor_bic : '');

				$name = self::clean($payment['debtor_name'], 70);
				self::el($doc, self::el($doc, $tx, 'Dbtr'), 'Nm', $name !== '' ? $name : 'NOTPROVIDED');
				self::el($doc, self::el($doc, self::el($doc, $tx, 'DbtrAcct'), 'Id'), 'IBAN', self::normalizeIban($payment['iban']));

				$remittance_info = self::clean($payment['info'], 140);

				if ($remittance_info !== '') {
					self::el($doc, self::el($doc, $tx, 'RmtInf'), 'Ustrd', $remittance_info);
				}
			}

			$summary[] = array('seq' => $group['seq'], 'date' => $group['date'], 'count' => count($group['payments']), 'total' => $group_cents / 100);
		}

		return array('xml' => $doc->saveXML(), 'count' => $total_count, 'total' => $total_cents / 100, 'groups' => $summary);
	}
}

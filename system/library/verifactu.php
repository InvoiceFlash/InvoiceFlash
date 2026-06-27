<?php
/**
 * VeriFactu client library.
 *
 * Self-contained adaptation of josemmo/Verifactu-PHP (https://github.com/josemmo/Verifactu-PHP, MIT license)
 * for this codebase, which has no Composer autoloading. Builds "RegistroAlta"/"RegistroAnulacion" billing
 * records, computes the SHA-256 hash chain ("huella") and submits them to the AEAT VERI*FACTU SOAP service
 * using plain cURL with a PKCS#12/PEM client certificate instead of Guzzle/UXML/Symfony Validator.
 *
 * This library does not constitute, by itself, a certified "Sistema Informatico de Facturacion" (SIF):
 * it is the responsibility of the integrator to use it according to Real Decreto 1007/2023.
 */
class VerifactuException extends Exception {
}

class Verifactu {
	// Invoice types (TipoFactura)
	const INVOICE_TYPE_FACTURA = 'F1';
	const INVOICE_TYPE_SIMPLIFICADA = 'F2';
	const INVOICE_TYPE_SUSTITUTIVA = 'F3';
	const INVOICE_TYPE_R1 = 'R1';
	const INVOICE_TYPE_R2 = 'R2';
	const INVOICE_TYPE_R3 = 'R3';
	const INVOICE_TYPE_R4 = 'R4';
	const INVOICE_TYPE_R5 = 'R5';

	// Corrective types (TipoRectificativa)
	const CORRECTIVE_TYPE_SUBSTITUTION = 'S';
	const CORRECTIVE_TYPE_DIFFERENCES = 'I';

	// Tax types (Impuesto)
	const TAX_TYPE_IVA = '01';
	const TAX_TYPE_IPSI = '02';
	const TAX_TYPE_IGIC = '03';
	const TAX_TYPE_OTHER = '05';

	// Regime types (ClaveRegimen), most common ones
	const REGIME_GENERAL = '01';
	const REGIME_EXPORT = '02';
	const REGIME_USED_GOODS = '03';
	const REGIME_INVESTMENT_GOLD = '04';
	const REGIME_TRAVEL_AGENCIES = '05';
	const REGIME_VAT_GROUP = '06';
	const REGIME_CASH = '07';
	const REGIME_IPSI_IGIC = '08';
	const REGIME_TRAVEL_AGENCIES_MEDIATION = '09';
	const REGIME_THIRD_PARTY_COLLECTION = '10';
	const REGIME_BUSINESS_PREMISES_LEASE = '11';
	const REGIME_PENDING_ACCRUAL_PUBLIC_ADMIN = '14';
	const REGIME_PENDING_ACCRUAL_SUCCESSIVE = '15';
	const REGIME_OSS_IOSS = '17';
	const REGIME_EQUIVALENCE_SURCHARGE = '18';
	const REGIME_AGRICULTURE = '19';
	const REGIME_SIMPLIFIED = '20';

	// Operation types (CalificacionOperacion / OperacionExenta)
	const OPERATION_SUBJECT = 'S1';
	const OPERATION_SUBJECT_REVERSE_CHARGE = 'S2';
	const OPERATION_NON_SUBJECT = 'N1';
	const OPERATION_NON_SUBJECT_LOCATION = 'N2';
	const OPERATION_EXEMPT_ART20 = 'E1';
	const OPERATION_EXEMPT_ART21 = 'E2';
	const OPERATION_EXEMPT_ART22 = 'E3';
	const OPERATION_EXEMPT_ART23_24 = 'E4';
	const OPERATION_EXEMPT_ART25 = 'E5';
	const OPERATION_EXEMPT_OTHER = 'E6';

	// Foreign recipient ID types (IDType)
	const FOREIGN_ID_VAT = '02';
	const FOREIGN_ID_PASSPORT = '03';
	const FOREIGN_ID_NATIONAL = '04';
	const FOREIGN_ID_RESIDENCE = '05';
	const FOREIGN_ID_OTHER = '06';
	const FOREIGN_ID_UNREGISTERED = '07';

	// XML namespaces used by the AEAT web service
	const NS_SOAPENV = 'http://schemas.xmlsoap.org/soap/envelope/';
	const NS_RECORD = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SuministroInformacion.xsd';
	const NS_AEAT = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SuministroLR.xsd';
	const NS_RESPONSE = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/RespuestaSuministro.xsd';

	// AEAT VERI*FACTU SOAP endpoints
	const ENDPOINT_PATH = '/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP';
	const ENDPOINT_PRODUCTION = 'https://www1.agenciatributaria.gob.es';
	const ENDPOINT_TESTING = 'https://prewww1.aeat.es';

	// AEAT QR validation endpoints
	const QR_PRODUCTION = 'https://www2.agenciatributaria.gob.es';
	const QR_TESTING = 'https://prewww2.aeat.es';

	private $taxpayerNif;
	private $taxpayerName;
	private $system;
	private $certificatePath;
	private $certificatePassword;
	private $caBundlePath;
	private $production = false; // defaults to AEAT's testing environment

	/**
	 * @param string $taxpayerNif  NIF of the party obliged to issue the invoices (ObligadoEmision)
	 * @param string $taxpayerName Name/company name of that party
	 * @param array  $system       Computer system ("SistemaInformatico") details, keys:
	 *                             vendor_nif, vendor_name, name, id, version, installation_number,
	 *                             only_verifactu (bool), supports_multi_taxpayer (bool), has_multi_taxpayer (bool)
	 */
	public function __construct($taxpayerNif, $taxpayerName, array $system) {
		$this->taxpayerNif = $taxpayerNif;
		$this->taxpayerName = $taxpayerName;
		$this->system = $system;
	}

	/**
	 * Set client certificate used to authenticate against the AEAT web service.
	 *
	 * @param string      $path     Path to a PKCS#12 (.p12/.pfx) bundle or a PEM certificate
	 * @param string|null $password Certificate password, or null if none
	 */
	public function setCertificate($path, $password = null) {
		$this->certificatePath = $path;
		$this->certificatePassword = $password;
	}

	/**
	 * Set a custom CA bundle to validate AEAT's server certificate (useful on Windows/XAMPP setups
	 * where PHP's cURL build has no default CA bundle configured).
	 */
	public function setCaBundle($path) {
		$this->caBundlePath = $path;
	}

	/**
	 * @param bool $production Pass true to use AEAT's production environment, false (default) for testing
	 */
	public function setProduction($production) {
		$this->production = (bool)$production;
	}

	/**
	 * Build a "RegistroAlta" (invoice registration) record, computing its hash chain.
	 *
	 * @param array $data See class docs / README for accepted keys. Required: invoice_number, issue_date,
	 *                    description, breakdown, total_tax_amount, total_amount. recipients is required
	 *                    unless invoice_type is F2/R5.
	 *
	 * @return array Record ready to be passed to send(), and to be persisted by the caller for future chaining
	 *
	 * @throws VerifactuException on missing/invalid data
	 */
	public function createRegistrationRecord(array $data) {
		$issuerId = isset($data['issuer_id']) ? $data['issuer_id'] : $this->taxpayerNif;
		$issuerName = isset($data['issuer_name']) ? $data['issuer_name'] : $this->taxpayerName;
		$invoiceType = isset($data['invoice_type']) ? $data['invoice_type'] : self::INVOICE_TYPE_FACTURA;

		$this->requireFields($data, array('invoice_number', 'issue_date', 'description', 'breakdown', 'total_tax_amount', 'total_amount'));
		$this->validateNif($issuerId, 'issuer_id');
		$this->validateAmount($data['total_tax_amount'], 'total_tax_amount');
		$this->validateAmount($data['total_amount'], 'total_amount');

		$recipients = array();
		$rawRecipients = isset($data['recipients']) ? $data['recipients'] : array();
		foreach ($rawRecipients as $recipient) {
			$recipients[] = $this->normalizeRecipient($recipient);
		}

		$hasRecipients = count($recipients) > 0;
		$noRecipientsAllowed = ($invoiceType === self::INVOICE_TYPE_SIMPLIFICADA || $invoiceType === self::INVOICE_TYPE_R5);
		if ($noRecipientsAllowed && $hasRecipients) {
			throw new VerifactuException('Invoice type ' . $invoiceType . ' cannot have recipients');
		}
		if (!$noRecipientsAllowed && !$hasRecipients) {
			throw new VerifactuException('Invoice type ' . $invoiceType . ' requires at least one recipient');
		}

		$breakdown = array();
		foreach ($data['breakdown'] as $details) {
			$breakdown[] = $this->normalizeBreakdownDetails($details);
		}
		if (!count($breakdown)) {
			throw new VerifactuException('breakdown must contain at least one item');
		}

		$correctedInvoices = array();
		$rawCorrectedInvoices = isset($data['corrected_invoices']) ? $data['corrected_invoices'] : array();
		foreach ($rawCorrectedInvoices as $invoiceId) {
			$correctedInvoices[] = $this->normalizeInvoiceId($invoiceId);
		}
		$replacedInvoices = array();
		$rawReplacedInvoices = isset($data['replaced_invoices']) ? $data['replaced_invoices'] : array();
		foreach ($rawReplacedInvoices as $invoiceId) {
			$replacedInvoices[] = $this->normalizeInvoiceId($invoiceId);
		}

		$previous = isset($data['previous']) && $data['previous'] !== null ? $this->normalizePrevious($data['previous']) : null;

		$record = array(
			'kind' => 'alta',
			'issuer_id' => $issuerId,
			'invoice_number' => (string)$data['invoice_number'],
			'issue_date' => $this->normalizeDate($data['issue_date']),
			'issuer_name' => $issuerName,
			'is_correction' => !empty($data['is_correction']),
			'is_prior_rejection' => array_key_exists('is_prior_rejection', $data) ? $data['is_prior_rejection'] : false,
			'invoice_type' => $invoiceType,
			'operation_date' => isset($data['operation_date']) ? $this->normalizeDate($data['operation_date']) : null,
			'description' => (string)$data['description'],
			'recipients' => $recipients,
			'corrective_type' => isset($data['corrective_type']) ? $data['corrective_type'] : null,
			'corrected_invoices' => $correctedInvoices,
			'replaced_invoices' => $replacedInvoices,
			'corrected_base_amount' => isset($data['corrected_base_amount']) ? $data['corrected_base_amount'] : null,
			'corrected_tax_amount' => isset($data['corrected_tax_amount']) ? $data['corrected_tax_amount'] : null,
			'breakdown' => $breakdown,
			'total_tax_amount' => $data['total_tax_amount'],
			'total_amount' => $data['total_amount'],
			'previous' => $previous,
		);

		$record['hashed_at'] = isset($data['hashed_at']) ? $data['hashed_at'] : date('c');
		$record['hash'] = $this->calculateRegistrationHash($record);

		return $record;
	}

	/**
	 * Build a "RegistroAnulacion" (invoice cancellation) record, computing its hash chain.
	 *
	 * Cancellation records should only be used in very specific cases; AEAT recommends issuing a
	 * corrective invoice (factura rectificativa) instead.
	 *
	 * @param array $data Keys: issuer_id (optional, defaults to taxpayer NIF), invoice_number, issue_date,
	 *                     previous (required: chain data of the record being cancelled),
	 *                     without_prior_record (bool, optional), is_prior_rejection (bool, optional)
	 *
	 * @return array Record ready to be passed to send()
	 *
	 * @throws VerifactuException on missing/invalid data
	 */
	public function createCancellationRecord(array $data) {
		$this->requireFields($data, array('invoice_number', 'issue_date', 'previous'));

		$issuerId = isset($data['issuer_id']) ? $data['issuer_id'] : $this->taxpayerNif;
		$this->validateNif($issuerId, 'issuer_id');

		$record = array(
			'kind' => 'anulacion',
			'issuer_id' => $issuerId,
			'invoice_number' => (string)$data['invoice_number'],
			'issue_date' => $this->normalizeDate($data['issue_date']),
			'without_prior_record' => !empty($data['without_prior_record']),
			'is_prior_rejection' => !empty($data['is_prior_rejection']),
			'previous' => $this->normalizePrevious($data['previous']),
		);

		$record['hashed_at'] = isset($data['hashed_at']) ? $data['hashed_at'] : date('c');
		$record['hash'] = $this->calculateCancellationHash($record);

		return $record;
	}

	/**
	 * Submit up to 1,000 registration/cancellation records to the AEAT VERI*FACTU web service.
	 *
	 * @param array $records One or more records returned by createRegistrationRecord()/createCancellationRecord()
	 *
	 * @return array{success:bool,status:?string,csv:?string,submitted_at:?string,wait_seconds:?int,items:array,
	 *               error:?string,http_code:int,request_xml:string,response_xml:?string}
	 *
	 * @throws VerifactuException if the certificate is missing or the HTTP request itself fails
	 */
	public function send(array $records) {
		if (!count($records)) {
			throw new VerifactuException('At least one record is required');
		}
		if (!$this->certificatePath) {
			throw new VerifactuException('A client certificate is required to call the AEAT web service, see setCertificate()');
		}

		$requestXml = $this->buildSoapRequest($records);

		$url = $this->getEndpoint() . self::ENDPOINT_PATH;
		$systemName = isset($this->system['name']) ? $this->system['name'] : 'InvoiceFlash';
		$systemVersion = isset($this->system['version']) ? $this->system['version'] : '1.0';

		$ch = curl_init($url);
		curl_setopt_array($ch, array(
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $requestXml,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: text/xml; charset=UTF-8',
				'User-Agent: Mozilla/5.0 (compatible; ' . $systemName . '/' . $systemVersion . ')',
			),
			CURLOPT_SSLCERT => $this->certificatePath,
			CURLOPT_SSLCERTTYPE => $this->isPkcs12($this->certificatePath) ? 'P12' : 'PEM',
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_TIMEOUT => 60,
		));
		if ($this->certificatePassword !== null) {
			curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->certificatePassword);
		}
		if ($this->caBundlePath) {
			curl_setopt($ch, CURLOPT_CAINFO, $this->caBundlePath);
		}

		$responseBody = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curlError = curl_error($ch);
		curl_close($ch);

		if ($responseBody === false) {
			throw new VerifactuException('cURL request to AEAT failed: ' . $curlError);
		}

		$result = array(
			'success' => false,
			'status' => null,
			'csv' => null,
			'submitted_at' => null,
			'wait_seconds' => null,
			'items' => array(),
			'error' => null,
			'http_code' => (int)$httpCode,
			'request_xml' => $requestXml,
			'response_xml' => $responseBody,
		);

		$this->parseSoapResponse($responseBody, $result);

		return $result;
	}

	/**
	 * Build the AEAT QR validation URL for a given invoice.
	 *
	 * @param string $issuerId      Issuer NIF
	 * @param string $invoiceNumber Invoice number
	 * @param string $issueDate     Issue date (Y-m-d)
	 * @param string $amount        Invoice total amount, formatted like "121.00"
	 * @param bool   $onlineMode    True for VERI*FACTU (online) mode, false for "No VERI*FACTU" (offline)
	 *
	 * @return string
	 */
	public function getQrUrl($issuerId, $invoiceNumber, $issueDate, $amount, $onlineMode = true) {
		$base = $this->production ? self::QR_PRODUCTION : self::QR_TESTING;
		$base .= '/wlpl/TIKE-CONT/';
		$base .= $onlineMode ? 'ValidarQR' : 'ValidarQRNoVerifactu';
		return $base . '?' . http_build_query(array(
			'nif' => $issuerId,
			'numserie' => $invoiceNumber,
			'fecha' => $this->normalizeDate($issueDate, 'd-m-Y'),
			'importe' => $amount,
		));
	}

	// -----------------------------------------------------------------------
	// Hashing
	// -----------------------------------------------------------------------

	/**
	 * NOTE: per AEAT's spec, values must NOT be URL-encoded when building this payload.
	 */
	private function calculateRegistrationHash(array $record) {
		$payload = 'IDEmisorFactura=' . $record['issuer_id'];
		$payload .= '&NumSerieFactura=' . $record['invoice_number'];
		$payload .= '&FechaExpedicionFactura=' . $this->normalizeDate($record['issue_date'], 'd-m-Y');
		$payload .= '&TipoFactura=' . $record['invoice_type'];
		$payload .= '&CuotaTotal=' . $record['total_tax_amount'];
		$payload .= '&ImporteTotal=' . $record['total_amount'];
		$payload .= '&Huella=' . ($record['previous'] ? $record['previous']['hash'] : '');
		$payload .= '&FechaHoraHusoGenRegistro=' . $record['hashed_at'];
		return strtoupper(hash('sha256', $payload));
	}

	private function calculateCancellationHash(array $record) {
		$payload = 'IDEmisorFacturaAnulada=' . $record['issuer_id'];
		$payload .= '&NumSerieFacturaAnulada=' . $record['invoice_number'];
		$payload .= '&FechaExpedicionFacturaAnulada=' . $this->normalizeDate($record['issue_date'], 'd-m-Y');
		$payload .= '&Huella=' . ($record['previous'] ? $record['previous']['hash'] : '');
		$payload .= '&FechaHoraHusoGenRegistro=' . $record['hashed_at'];
		return strtoupper(hash('sha256', $payload));
	}

	// -----------------------------------------------------------------------
	// XML export
	// -----------------------------------------------------------------------

	private function buildSoapRequest(array $records) {
		$dom = new DOMDocument('1.0', 'UTF-8');

		$envelope = $dom->createElementNS(self::NS_SOAPENV, 'soapenv:Envelope');
		$envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sum', self::NS_AEAT);
		$envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sum1', self::NS_RECORD);
		$dom->appendChild($envelope);

		$envelope->appendChild($dom->createElementNS(self::NS_SOAPENV, 'soapenv:Header'));
		$body = $this->addEl($dom, $envelope, self::NS_SOAPENV, 'soapenv:Body');
		$root = $this->addEl($dom, $body, self::NS_AEAT, 'sum:RegFactuSistemaFacturacion');

		// Header
		$cabecera = $this->addEl($dom, $root, self::NS_AEAT, 'sum:Cabecera');
		$obligado = $this->addEl($dom, $cabecera, self::NS_RECORD, 'sum1:ObligadoEmision');
		$this->addEl($dom, $obligado, self::NS_RECORD, 'sum1:NombreRazon', $this->taxpayerName);
		$this->addEl($dom, $obligado, self::NS_RECORD, 'sum1:NIF', $this->taxpayerNif);

		// Records
		foreach ($records as $record) {
			$registroFactura = $this->addEl($dom, $root, self::NS_AEAT, 'sum:RegistroFactura');
			$this->exportRecord($dom, $registroFactura, $record);
		}

		return $dom->saveXML();
	}

	private function exportRecord(DOMDocument $dom, DOMElement $parent, array $record) {
		$isCancellation = ($record['kind'] === 'anulacion');
		$elementName = $isCancellation ? 'sum1:RegistroAnulacion' : 'sum1:RegistroAlta';
		$el = $this->addEl($dom, $parent, self::NS_RECORD, $elementName);

		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:IDVersion', '1.0');

		if ($isCancellation) {
			$this->exportCancellationProperties($dom, $el, $record);
		} else {
			$this->exportRegistrationProperties($dom, $el, $record);
		}

		// Chaining
		$encadenamiento = $this->addEl($dom, $el, self::NS_RECORD, 'sum1:Encadenamiento');
		if (!$record['previous']) {
			$this->addEl($dom, $encadenamiento, self::NS_RECORD, 'sum1:PrimerRegistro', 'S');
		} else {
			$registroAnterior = $this->addEl($dom, $encadenamiento, self::NS_RECORD, 'sum1:RegistroAnterior');
			$this->exportInvoiceId($dom, $registroAnterior, $record['previous'], false);
			$this->addEl($dom, $registroAnterior, self::NS_RECORD, 'sum1:Huella', $record['previous']['hash']);
		}

		// Computer system
		$this->exportSystem($dom, $el);

		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:FechaHoraHusoGenRegistro', $record['hashed_at']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:TipoHuella', '01'); // SHA-256
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:Huella', $record['hash']);
	}

	private function exportRegistrationProperties(DOMDocument $dom, DOMElement $el, array $record) {
		$idFactura = $this->addEl($dom, $el, self::NS_RECORD, 'sum1:IDFactura');
		$this->exportInvoiceId($dom, $idFactura, $record, false);

		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:NombreRazonEmisor', $record['issuer_name']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:Subsanacion', $record['is_correction'] ? 'S' : 'N');
		if ($record['is_prior_rejection'] === true) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:RechazoPrevio', 'S');
		} elseif ($record['is_prior_rejection'] === null) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:RechazoPrevio', 'X');
		}

		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:TipoFactura', $record['invoice_type']);

		if ($record['corrective_type'] !== null) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:TipoRectificativa', $record['corrective_type']);
		}
		if (count($record['corrected_invoices'])) {
			$wrap = $this->addEl($dom, $el, self::NS_RECORD, 'sum1:FacturasRectificadas');
			foreach ($record['corrected_invoices'] as $invoiceId) {
				$item = $this->addEl($dom, $wrap, self::NS_RECORD, 'sum1:IDFacturaRectificada');
				$this->exportInvoiceId($dom, $item, $invoiceId, false);
			}
		}
		if (count($record['replaced_invoices'])) {
			$wrap = $this->addEl($dom, $el, self::NS_RECORD, 'sum1:FacturasSustituidas');
			foreach ($record['replaced_invoices'] as $invoiceId) {
				$item = $this->addEl($dom, $wrap, self::NS_RECORD, 'sum1:IDFacturaSustituida');
				$this->exportInvoiceId($dom, $item, $invoiceId, false);
			}
		}
		if ($record['corrected_base_amount'] !== null && $record['corrected_tax_amount'] !== null) {
			$wrap = $this->addEl($dom, $el, self::NS_RECORD, 'sum1:ImporteRectificacion');
			$this->addEl($dom, $wrap, self::NS_RECORD, 'sum1:BaseRectificada', $record['corrected_base_amount']);
			$this->addEl($dom, $wrap, self::NS_RECORD, 'sum1:CuotaRectificada', $record['corrected_tax_amount']);
		}

		if ($record['operation_date'] !== null) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:FechaOperacion', $this->normalizeDate($record['operation_date'], 'd-m-Y'));
		}

		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:DescripcionOperacion', $record['description']);

		if (count($record['recipients'])) {
			$wrap = $this->addEl($dom, $el, self::NS_RECORD, 'sum1:Destinatarios');
			foreach ($record['recipients'] as $recipient) {
				$item = $this->addEl($dom, $wrap, self::NS_RECORD, 'sum1:IDDestinatario');
				$this->addEl($dom, $item, self::NS_RECORD, 'sum1:NombreRazon', $recipient['name']);
				if (isset($recipient['nif'])) {
					$this->addEl($dom, $item, self::NS_RECORD, 'sum1:NIF', $recipient['nif']);
				} else {
					$idOtro = $this->addEl($dom, $item, self::NS_RECORD, 'sum1:IDOtro');
					$this->addEl($dom, $idOtro, self::NS_RECORD, 'sum1:CodigoPais', $recipient['country']);
					$this->addEl($dom, $idOtro, self::NS_RECORD, 'sum1:IDType', $recipient['id_type']);
					$this->addEl($dom, $idOtro, self::NS_RECORD, 'sum1:ID', $recipient['id']);
				}
			}
		}

		$desglose = $this->addEl($dom, $el, self::NS_RECORD, 'sum1:Desglose');
		foreach ($record['breakdown'] as $details) {
			$this->exportBreakdownDetails($dom, $desglose, $details);
		}

		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:CuotaTotal', $record['total_tax_amount']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:ImporteTotal', $record['total_amount']);
	}

	private function exportCancellationProperties(DOMDocument $dom, DOMElement $el, array $record) {
		$idFactura = $this->addEl($dom, $el, self::NS_RECORD, 'sum1:IDFactura');
		$this->exportInvoiceId($dom, $idFactura, $record, true);

		if ($record['without_prior_record']) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:SinRegistroPrevio', 'S');
		}
		if ($record['is_prior_rejection']) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:RechazoPrevio', 'S');
		}
	}

	private function exportBreakdownDetails(DOMDocument $dom, DOMElement $parent, array $details) {
		$el = $this->addEl($dom, $parent, self::NS_RECORD, 'sum1:DetalleDesglose');
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:Impuesto', $details['tax_type']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:ClaveRegimen', $details['regime_type']);
		$this->addEl(
			$dom,
			$el,
			self::NS_RECORD,
			$this->isExemptOperation($details['operation_type']) ? 'sum1:OperacionExenta' : 'sum1:CalificacionOperacion',
			$details['operation_type']
		);
		if ($details['tax_rate'] !== null) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:TipoImpositivo', $details['tax_rate']);
		}
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:BaseImponibleOimporteNoSujeto', $details['base_amount']);
		if ($details['tax_amount'] !== null) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:CuotaRepercutida', $details['tax_amount']);
		}
		if ($details['surcharge_rate'] !== null) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:TipoRecargoEquivalencia', $details['surcharge_rate']);
		}
		if ($details['surcharge_amount'] !== null) {
			$this->addEl($dom, $el, self::NS_RECORD, 'sum1:CuotaRecargoEquivalencia', $details['surcharge_amount']);
		}
	}

	private function exportInvoiceId(DOMDocument $dom, DOMElement $el, array $invoiceId, $isCancellation) {
		$suffix = $isCancellation ? 'Anulada' : '';
		$this->addEl($dom, $el, self::NS_RECORD, "sum1:IDEmisorFactura$suffix", $invoiceId['issuer_id']);
		$this->addEl($dom, $el, self::NS_RECORD, "sum1:NumSerieFactura$suffix", $invoiceId['invoice_number']);
		$this->addEl($dom, $el, self::NS_RECORD, "sum1:FechaExpedicionFactura$suffix", $this->normalizeDate($invoiceId['issue_date'], 'd-m-Y'));
	}

	private function exportSystem(DOMDocument $dom, DOMElement $parent) {
		$system = $this->system;
		$el = $this->addEl($dom, $parent, self::NS_RECORD, 'sum1:SistemaInformatico');
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:NombreRazon', $system['vendor_name']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:NIF', $system['vendor_nif']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:NombreSistemaInformatico', $system['name']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:IdSistemaInformatico', $system['id']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:Version', $system['version']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:NumeroInstalacion', $system['installation_number']);
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:TipoUsoPosibleSoloVerifactu', !empty($system['only_verifactu']) ? 'S' : 'N');
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:TipoUsoPosibleMultiOT', !empty($system['supports_multi_taxpayer']) ? 'S' : 'N');
		$this->addEl($dom, $el, self::NS_RECORD, 'sum1:IndicadorMultiplesOT', !empty($system['has_multi_taxpayer']) ? 'S' : 'N');
	}

	private function addEl(DOMDocument $dom, DOMElement $parent, $namespace, $name, $text = null) {
		$el = $dom->createElementNS($namespace, $name);
		if ($text !== null) {
			$el->appendChild($dom->createTextNode($text));
		}
		$parent->appendChild($el);
		return $el;
	}

	// -----------------------------------------------------------------------
	// Response parsing
	// -----------------------------------------------------------------------

	private function parseSoapResponse($responseBody, array &$result) {
		$dom = new DOMDocument();
		$internalErrors = libxml_use_internal_errors(true);
		$loaded = $dom->loadXML($responseBody);
		libxml_use_internal_errors($internalErrors);

		if (!$loaded) {
			$result['error'] = 'Failed to parse XML response from AEAT';
			return;
		}

		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('soapenv', self::NS_SOAPENV);
		$xpath->registerNamespace('tikr', self::NS_RESPONSE);
		$xpath->registerNamespace('tik', self::NS_RECORD);

		$fault = $xpath->query('//soapenv:Body/soapenv:Fault/faultstring');
		if ($fault->length > 0) {
			$result['error'] = $fault->item(0)->textContent;
			return;
		}

		$root = $xpath->query('//soapenv:Body/tikr:RespuestaRegFactuSistemaFacturacion');
		if ($root->length === 0) {
			$result['error'] = 'Missing <RespuestaRegFactuSistemaFacturacion/> element in AEAT response';
			return;
		}
		$root = $root->item(0);

		$csv = $xpath->query('tikr:CSV', $root);
		if ($csv->length > 0) {
			$result['csv'] = $csv->item(0)->textContent;
		}

		$submittedAt = $xpath->query('tikr:DatosPresentacion/tik:TimestampPresentacion', $root);
		if ($submittedAt->length > 0) {
			$result['submitted_at'] = $submittedAt->item(0)->textContent;
		}

		$waitSeconds = $xpath->query('tikr:TiempoEsperaEnvio', $root);
		if ($waitSeconds->length > 0) {
			$result['wait_seconds'] = (int)$waitSeconds->item(0)->textContent;
		}

		$status = $xpath->query('tikr:EstadoEnvio', $root);
		if ($status->length > 0) {
			$result['status'] = $status->item(0)->textContent;
		}

		foreach ($xpath->query('tikr:RespuestaLinea', $root) as $lineEl) {
			$item = array(
				'issuer_id' => $this->xpathText($xpath, 'tikr:IDFactura/tik:IDEmisorFactura', $lineEl),
				'invoice_number' => $this->xpathText($xpath, 'tikr:IDFactura/tik:NumSerieFactura', $lineEl),
				'issue_date' => $this->xpathText($xpath, 'tikr:IDFactura/tik:FechaExpedicionFactura', $lineEl),
				'record_type' => $this->xpathText($xpath, 'tikr:Operacion/tik:TipoOperacion', $lineEl),
				'is_correction' => $this->xpathText($xpath, 'tikr:Operacion/tik:Subsanacion', $lineEl) === 'S',
				'status' => $this->xpathText($xpath, 'tikr:EstadoRegistro', $lineEl),
				'error_code' => $this->xpathText($xpath, 'tikr:CodigoErrorRegistro', $lineEl),
				'error_description' => $this->xpathText($xpath, 'tikr:DescripcionErrorRegistro', $lineEl),
			);
			$result['items'][] = $item;
		}

		$result['success'] = ($result['status'] === 'Correcto' || $result['status'] === 'ParcialmenteCorrecto');
	}

	private function xpathText(DOMXPath $xpath, $query, DOMElement $context) {
		$nodes = $xpath->query($query, $context);
		return $nodes->length > 0 ? $nodes->item(0)->textContent : null;
	}

	// -----------------------------------------------------------------------
	// Normalization / validation helpers
	// -----------------------------------------------------------------------

	private function requireFields(array $data, array $fields) {
		foreach ($fields as $field) {
			if (!isset($data[$field]) || $data[$field] === '') {
				throw new VerifactuException("Missing required field: $field");
			}
		}
	}

	private function validateNif($nif, $field) {
		if (!is_string($nif) || strlen($nif) !== 9) {
			throw new VerifactuException("$field must be a 9-character NIF");
		}
	}

	private function validateAmount($amount, $field) {
		if (!preg_match('/^-?\d{1,12}\.\d{2}$/', (string)$amount)) {
			throw new VerifactuException("$field must be a decimal amount with 2 decimals, e.g. \"121.00\"");
		}
	}

	private function isExemptOperation($operationType) {
		return in_array($operationType, array(
			self::OPERATION_EXEMPT_ART20,
			self::OPERATION_EXEMPT_ART21,
			self::OPERATION_EXEMPT_ART22,
			self::OPERATION_EXEMPT_ART23_24,
			self::OPERATION_EXEMPT_ART25,
			self::OPERATION_EXEMPT_OTHER,
		), true);
	}

	private function normalizeRecipient(array $recipient) {
		if (!isset($recipient['name']) || $recipient['name'] === '') {
			throw new VerifactuException('Recipient name is required');
		}
		if (isset($recipient['nif'])) {
			$this->validateNif($recipient['nif'], 'recipient nif');
			return array('name' => $recipient['name'], 'nif' => $recipient['nif']);
		}
		foreach (array('country', 'id_type', 'id') as $field) {
			if (empty($recipient[$field])) {
				throw new VerifactuException("Foreign recipient requires '$field'");
			}
		}
		return array(
			'name' => $recipient['name'],
			'country' => $recipient['country'],
			'id_type' => $recipient['id_type'],
			'id' => $recipient['id'],
		);
	}

	private function normalizeBreakdownDetails(array $details) {
		foreach (array('tax_type', 'regime_type', 'operation_type', 'base_amount') as $field) {
			if (!isset($details[$field]) || $details[$field] === '') {
				throw new VerifactuException("Breakdown item missing '$field'");
			}
		}
		$this->validateAmount($details['base_amount'], 'breakdown base_amount');

		$normalized = array(
			'tax_type' => $details['tax_type'],
			'regime_type' => $details['regime_type'],
			'operation_type' => $details['operation_type'],
			'base_amount' => $details['base_amount'],
			'tax_rate' => isset($details['tax_rate']) ? $details['tax_rate'] : null,
			'tax_amount' => isset($details['tax_amount']) ? $details['tax_amount'] : null,
			'surcharge_rate' => isset($details['surcharge_rate']) ? $details['surcharge_rate'] : null,
			'surcharge_amount' => isset($details['surcharge_amount']) ? $details['surcharge_amount'] : null,
		);

		if ($normalized['tax_amount'] !== null) {
			$this->validateAmount($normalized['tax_amount'], 'breakdown tax_amount');
		}
		if ($normalized['surcharge_amount'] !== null) {
			$this->validateAmount($normalized['surcharge_amount'], 'breakdown surcharge_amount');
		}

		return $normalized;
	}

	private function normalizeInvoiceId(array $invoiceId) {
		foreach (array('issuer_id', 'invoice_number', 'issue_date') as $field) {
			if (empty($invoiceId[$field])) {
				throw new VerifactuException("Invoice identifier missing '$field'");
			}
		}
		return array(
			'issuer_id' => $invoiceId['issuer_id'],
			'invoice_number' => $invoiceId['invoice_number'],
			'issue_date' => $this->normalizeDate($invoiceId['issue_date']),
		);
	}

	private function normalizePrevious(array $previous) {
		foreach (array('issuer_id', 'invoice_number', 'issue_date', 'hash') as $field) {
			if (empty($previous[$field])) {
				throw new VerifactuException("previous record reference missing '$field'");
			}
		}
		if (!preg_match('/^[0-9A-F]{64}$/', $previous['hash'])) {
			throw new VerifactuException('previous.hash must be a 64-character uppercase SHA-256 hex digest');
		}
		return array(
			'issuer_id' => $previous['issuer_id'],
			'invoice_number' => $previous['invoice_number'],
			'issue_date' => $this->normalizeDate($previous['issue_date']),
			'hash' => $previous['hash'],
		);
	}

	/**
	 * Accepts a 'Y-m-d' string, any strtotime()-parsable string, or a DateTime instance,
	 * and returns it formatted as requested (defaults to 'Y-m-d' for internal storage).
	 */
	private function normalizeDate($date, $format = 'Y-m-d') {
		if ($date instanceof DateTime) {
			return $date->format($format);
		}
		$timestamp = strtotime((string)$date);
		if ($timestamp === false) {
			throw new VerifactuException("Invalid date: $date");
		}
		return date($format, $timestamp);
	}

	private function isPkcs12($path) {
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		return ($ext === 'p12' || $ext === 'pfx');
	}

	private function getEndpoint() {
		return $this->production ? self::ENDPOINT_PRODUCTION : self::ENDPOINT_TESTING;
	}
}

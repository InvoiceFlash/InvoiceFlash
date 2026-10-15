<?php
/**
 * Cliente del servicio web de proveedores de FACe (Punto General de Entrada de Facturas
 * Electronicas, SSPP v2) sin Composer ni extension SOAP: solo DOM, openssl y curl.
 *
 * Cada peticion SOAP 1.1 va firmada con WS-Security (BinarySecurityToken X509v3 + firma
 * RSA-SHA512 con canonicalizacion exclusiva sobre el Timestamp y el Body), que es lo que
 * exige FACe; el certificado es el mismo con el que se firma la factura Facturae y tiene que
 * estar dado de alta en el portal de FACe como certificado del proveedor.
 *
 * Esquema de mensajes: https://se-face-webservice.redsara.es/facturasspp2?wsdl
 */
class FaceException extends Exception {
}

class Face {
	const NS_WEB = 'https://webservice.face.gob.es';
	const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
	const NS_DS = 'http://www.w3.org/2000/09/xmldsig#';
	const NS_WSU = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
	const NS_WSSE = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

	const URL_PRODUCTION = 'https://webservice.face.gob.es/facturasspp2';
	const URL_STAGING = 'https://se-face-webservice.redsara.es/facturasspp2';

	const REQUEST_EXPIRATION = 60; // segundos de validez del Timestamp

	private $certPem;
	private $keyPem;
	private $production;

	/**
	 * @param string $certificatePath  .p12/.pfx, o .pem con certificado y clave
	 * @param string $password         Contrasena del certificado
	 * @param bool   $production       false = entorno de pruebas (se-face-webservice.redsara.es)
	 *
	 * @throws FaceException
	 */
	public function __construct($certificatePath, $password, $production = false) {
		list($this->certPem, $this->keyPem) = $this->loadCertificate($certificatePath, $password);

		$this->production = (bool)$production;
	}

	public function getEndpointUrl() {
		return $this->production ? self::URL_PRODUCTION : self::URL_STAGING;
	}

	/**
	 * Envia una factura Facturae ya firmada.
	 *
	 * @return array numeroRegistro, organoGestor, unidadTramitadora, oficinaContable,
	 *               identificadorEmisor, fechaRecepcion, codigoSeguimiento
	 *
	 * @throws FaceException si FACe rechaza la factura (con su codigo y descripcion)
	 */
	public function sendInvoice($email, $xml, $filename) {
		$body = '<web:enviarFactura><request>'
			. '<correo>' . $this->escape($email) . '</correo>'
			. '<factura>'
			. '<factura>' . base64_encode($xml) . '</factura>'
			. '<nombre>' . $this->escape($filename) . '</nombre>'
			. '<mime>application/xml</mime>'
			. '</factura>'
			. '<anexos></anexos>'
			. '</request></web:enviarFactura>';

		$response = $this->request('enviarFactura', $body);

		$this->assertResult($response);

		$invoice = isset($response['factura']) && is_array($response['factura']) ? $response['factura'] : array();

		if (empty($invoice['numeroRegistro'])) {
			throw new FaceException('FACe no devolvio el numero de registro de la factura.');
		}

		$invoice['codigoSeguimiento'] = isset($response['resultado']['codigoSeguimiento']) ? (string)$response['resultado']['codigoSeguimiento'] : '';

		return $invoice;
	}

	/**
	 * Estado de tramitacion y de anulacion de una factura registrada.
	 *
	 * @return array tramitacion => {codigo, descripcion, motivo}, anulacion => {codigo, descripcion, motivo}
	 *
	 * @throws FaceException
	 */
	public function getInvoice($registry_number) {
		$response = $this->request('consultarFactura', '<web:consultarFactura><numeroRegistro>' . $this->escape($registry_number) . '</numeroRegistro></web:consultarFactura>');

		$this->assertResult($response);

		$invoice = isset($response['factura']) && is_array($response['factura']) ? $response['factura'] : array();

		return array(
			'tramitacion' => $this->state($invoice, 'tramitacion'),
			'anulacion'   => $this->state($invoice, 'anulacion')
		);
	}

	/**
	 * Pide la anulacion de una factura registrada (la acepta o rechaza el organo gestor).
	 *
	 * @throws FaceException
	 */
	public function cancelInvoice($registry_number, $reason) {
		$response = $this->request('anularFactura', '<web:anularFactura><numeroRegistro>' . $this->escape($registry_number) . '</numeroRegistro><motivo>' . $this->escape($reason) . '</motivo></web:anularFactura>');

		$this->assertResult($response);

		return isset($response['factura']['mensaje']) ? (string)$response['factura']['mensaje'] : '';
	}

	/**
	 * Catalogo de estados de FACe; sirve tambien para probar la conexion y el certificado.
	 *
	 * @return array lista de {codigo, nombre, descripcion}
	 *
	 * @throws FaceException
	 */
	public function getStatuses() {
		$response = $this->request('consultarEstados', '<web:consultarEstados></web:consultarEstados>');

		$this->assertResult($response);

		$statuses = array();

		if (isset($response['estados']['estado'])) {
			$list = $response['estados']['estado'];

			if (isset($list['codigo'])) {
				$list = array($list);
			}

			foreach ($list as $status) {
				$statuses[] = $status;
			}
		}

		return $statuses;
	}

	private function state($invoice, $key) {
		$state = isset($invoice[$key]) && is_array($invoice[$key]) ? $invoice[$key] : array();

		return array(
			'codigo'      => isset($state['codigo']) ? (string)$state['codigo'] : '',
			'descripcion' => isset($state['descripcion']) ? (string)$state['descripcion'] : '',
			'motivo'      => isset($state['motivo']) && !is_array($state['motivo']) ? (string)$state['motivo'] : ''
		);
	}

	// FACe responde resultado/codigo = 0 cuando la operacion ha ido bien.
	private function assertResult($response) {
		$result = isset($response['resultado']) && is_array($response['resultado']) ? $response['resultado'] : array();

		if (!isset($result['codigo'])) {
			throw new FaceException('Respuesta de FACe no reconocida.');
		}

		if ((string)$result['codigo'] !== '0') {
			$description = isset($result['descripcion']) && !is_array($result['descripcion']) ? (string)$result['descripcion'] : '';

			throw new FaceException('FACe (' . $result['codigo'] . '): ' . $description);
		}
	}

	/**
	 * Envuelve $body en un sobre SOAP firmado, lo envia y devuelve el contenido de <return>
	 * como array.
	 */
	private function request($action, $body) {
		$envelope = $this->buildEnvelope($body);

		$ch = curl_init();

		curl_setopt_array($ch, array(
			CURLOPT_URL            => $this->getEndpointUrl(),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $envelope,
			CURLOPT_TIMEOUT        => 60,
			CURLOPT_CONNECTTIMEOUT => 15,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_HTTPHEADER     => array(
				'Content-Type: text/xml; charset=UTF-8',
				'SOAPAction: ' . self::NS_WEB . '#' . $action
			),
			CURLOPT_USERAGENT      => 'InvoiceFlash-FACe'
		));

		$raw = curl_exec($ch);
		$curl_error = curl_error($ch);
		$http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if ($raw === false) {
			throw new FaceException('No se pudo conectar con FACe: ' . $curl_error);
		}

		$doc = new DOMDocument();

		if (!@$doc->loadXML($raw)) {
			throw new FaceException('FACe respondio algo que no es XML (HTTP ' . $http_code . ').');
		}

		$xpath = new DOMXPath($doc);
		$xpath->registerNamespace('s', self::NS_SOAP);

		$fault = $xpath->query('//s:Body/s:Fault/faultstring');

		if ($fault->length) {
			throw new FaceException('FACe: ' . trim($fault->item(0)->textContent));
		}

		$return = $xpath->query('//s:Body/*/return');

		if (!$return->length) {
			throw new FaceException('Respuesta de FACe sin contenido (HTTP ' . $http_code . ').');
		}

		return $this->toArray($return->item(0));
	}

	// Hijos repetidos -> lista; sin hijos -> texto.
	private function toArray(DOMElement $element) {
		$children = array();

		foreach ($element->childNodes as $child) {
			if ($child instanceof DOMElement) {
				$children[] = $child;
			}
		}

		if (!$children) {
			return trim($element->textContent);
		}

		$result = array();

		foreach ($children as $child) {
			$name = $child->localName;
			$value = $this->toArray($child);

			if (isset($result[$name])) {
				if (!is_array($result[$name]) || !isset($result[$name][0])) {
					$result[$name] = array($result[$name]);
				}

				$result[$name][] = $value;
			} else {
				$result[$name] = $value;
			}
		}

		return $result;
	}

	private function buildEnvelope($body) {
		$id = $this->randomId();

		$body_id = 'BodyId-' . $id;
		$cert_id = 'CertId-' . $id;
		$key_id = 'KeyId-' . $id;
		$str_id = 'SecTokId-' . $id;
		$timestamp_id = 'TimestampId-' . $id;
		$sig_id = 'SignatureId-' . $id;

		$created = time();

		$cert_body = preg_replace('/-----(BEGIN|END) CERTIFICATE-----|\s+/', '', $this->certPem);

		$sha512 = 'http://www.w3.org/2001/04/xmlenc#sha512';

		$xml = '<?xml version="1.0" encoding="UTF-8"?>'
			. '<soapenv:Envelope xmlns:soapenv="' . self::NS_SOAP . '" xmlns:web="' . self::NS_WEB . '" xmlns:ds="' . self::NS_DS . '" xmlns:wsu="' . self::NS_WSU . '" xmlns:wsse="' . self::NS_WSSE . '">'
			. '<soapenv:Header>'
			. '<wsse:Security soapenv:mustUnderstand="1">'
			. '<wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" wsu:Id="' . $cert_id . '" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3">' . $cert_body . '</wsse:BinarySecurityToken>'
			. '<ds:Signature Id="' . $sig_id . '">'
			. '<ds:SignedInfo>'
			. '<ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"></ds:CanonicalizationMethod>'
			. '<ds:SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha512"></ds:SignatureMethod>'
			. '<ds:Reference URI="#' . $timestamp_id . '"><ds:DigestMethod Algorithm="' . $sha512 . '"></ds:DigestMethod><ds:DigestValue></ds:DigestValue></ds:Reference>'
			. '<ds:Reference URI="#' . $body_id . '"><ds:DigestMethod Algorithm="' . $sha512 . '"></ds:DigestMethod><ds:DigestValue></ds:DigestValue></ds:Reference>'
			. '</ds:SignedInfo>'
			. '<ds:SignatureValue></ds:SignatureValue>'
			. '<ds:KeyInfo Id="' . $key_id . '"><wsse:SecurityTokenReference wsu:Id="' . $str_id . '"><wsse:Reference URI="#' . $cert_id . '" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"></wsse:Reference></wsse:SecurityTokenReference></ds:KeyInfo>'
			. '</ds:Signature>'
			. '<wsu:Timestamp wsu:Id="' . $timestamp_id . '"><wsu:Created>' . date('c', $created) . '</wsu:Created><wsu:Expires>' . date('c', $created + self::REQUEST_EXPIRATION) . '</wsu:Expires></wsu:Timestamp>'
			. '</wsse:Security>'
			. '</soapenv:Header>'
			. '<soapenv:Body wsu:Id="' . $body_id . '">' . $body . '</soapenv:Body>'
			. '</soapenv:Envelope>';

		$doc = new DOMDocument();
		$doc->preserveWhiteSpace = true;
		$doc->loadXML($xml);

		$xpath = new DOMXPath($doc);
		$xpath->registerNamespace('ds', self::NS_DS);
		$xpath->registerNamespace('wsu', self::NS_WSU);
		$xpath->registerNamespace('s', self::NS_SOAP);

		$timestamp = $xpath->query('//wsu:Timestamp')->item(0);
		$body_node = $xpath->query('//s:Body')->item(0);
		$digests = $xpath->query('//ds:SignedInfo/ds:Reference/ds:DigestValue');

		$digests->item(0)->appendChild($doc->createTextNode(base64_encode(hash('sha512', $timestamp->C14N(true, false), true))));
		$digests->item(1)->appendChild($doc->createTextNode(base64_encode(hash('sha512', $body_node->C14N(true, false), true))));

		$signed_info = $xpath->query('//ds:SignedInfo')->item(0);

		$key = openssl_pkey_get_private($this->keyPem);

		if (!$key || !openssl_sign($signed_info->C14N(true, false), $signature, $key, OPENSSL_ALGO_SHA512)) {
			throw new FaceException('No se pudo firmar la peticion a FACe con el certificado.');
		}

		$xpath->query('//ds:SignatureValue')->item(0)->appendChild($doc->createTextNode(base64_encode($signature)));

		return $doc->saveXML();
	}

	private function loadCertificate($path, $password) {
		if (!is_file($path) || !is_readable($path)) {
			throw new FaceException('No se encuentra el certificado digital configurado en Ajustes.');
		}

		$raw = file_get_contents($path);
		$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

		if ($extension === 'p12' || $extension === 'pfx') {
			if (!openssl_pkcs12_read($raw, $certs, (string)$password)) {
				throw new FaceException('No se pudo leer el certificado .p12/.pfx (revisa la contrasena).');
			}

			return array($certs['cert'], $certs['pkey']);
		}

		$cert = openssl_x509_read($raw);

		if (!$cert) {
			throw new FaceException('No se pudo leer el certificado.');
		}

		openssl_x509_export($cert, $cert_pem);

		$key = openssl_pkey_get_private($raw, (string)$password);

		if (!$key) {
			throw new FaceException('El certificado no incluye una clave privada utilizable (usa un .p12/.pfx, o un .pem con la clave incluida).');
		}

		openssl_pkey_export($key, $key_pem);

		return array($cert_pem, $key_pem);
	}

	private function escape($text) {
		return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
	}

	private function randomId() {
		return strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 16));
	}
}

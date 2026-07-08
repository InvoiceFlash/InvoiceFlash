<?php
/**
 * XAdES-EPES signer for Facturae invoices.
 *
 * Self-contained adaptation of the signing approach used by josemmo/facturae-php
 * (https://github.com/josemmo/facturae-php, MIT license) for this codebase, which has no
 * Composer autoloading: builds an enveloped XAdES-EPES <ds:Signature> referencing the
 * official "Politica de Firma FacturaE v3.1", using plain openssl_*/DOMDocument instead of
 * that library's phpseclib-based toolchain.
 */
class FacturaeSignerException extends Exception {
}

class FacturaeSigner {
	const NS_DS = 'http://www.w3.org/2000/09/xmldsig#';
	const NS_XADES = 'http://uri.etsi.org/01903/v1.3.2#';

	// Official, immutable Facturae signature policy (hash of the published policy PDF).
	const POLICY_IDENTIFIER = 'http://www.facturae.es/politica_de_firma_formato_facturae/politica_de_firma_formato_facturae_v3_1.pdf';
	const POLICY_DESCRIPTION = 'Politica de Firma FacturaE v3.1';
	const POLICY_DIGEST_METHOD = 'http://www.w3.org/2000/09/xmldsig#sha1';
	const POLICY_DIGEST_VALUE = 'Ohixl6upD6av8N7pEvDABhEL6hM=';

	/**
	 * Checks that the certificate file exists, its password is correct and it has a usable
	 * private key, without signing anything. Lets callers fail fast before building the invoice.
	 *
	 * @throws FacturaeSignerException
	 */
	public function testCertificate($certificatePath, $certificatePassword) {
		$this->loadCertificate($certificatePath, $certificatePassword);
	}

	/**
	 * @param string $xml                  Unsigned Facturae XML, as produced for the fe:Facturae root
	 * @param string $certificatePath      Path to a PKCS#12 (.p12/.pfx) bundle, or a PEM file (cert + key)
	 * @param string $certificatePassword  Certificate/key passphrase
	 * @param string $claimedRole          XAdES ClaimedRole, e.g. "emisor"
	 *
	 * @return string Signed XML
	 *
	 * @throws FacturaeSignerException
	 */
	public function sign($xml, $certificatePath, $certificatePassword, $claimedRole = 'emisor') {
		list($certPem, $keyPem) = $this->loadCertificate($certificatePath, $certificatePassword);

		$certDer = $this->pemToDer($certPem);
		$certInfo = openssl_x509_parse($certPem);

		if (!$certInfo) {
			throw new FacturaeSignerException('No se pudo interpretar el certificado');
		}

		$privateKey = openssl_pkey_get_private($keyPem);

		if (!$privateKey) {
			throw new FacturaeSignerException('No se pudo leer la clave privada del certificado');
		}

		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->preserveWhiteSpace = true;

		if (!$doc->loadXML($xml)) {
			throw new FacturaeSignerException('El XML de la factura no es valido');
		}

		$root = $doc->documentElement;

		// Reference to the whole (still unsigned) document: this is exactly what applying the
		// enveloped-signature transform would yield once the Signature element exists below.
		$documentDigest = base64_encode(hash('sha256', $doc->C14N(false, false), true));

		$suffix = $this->uniqueSuffix();
		$signatureId = 'Signature-' . $suffix;
		$signedInfoId = 'SignedInfo-' . $suffix;
		$signatureValueId = 'SignatureValue-' . $suffix;
		$keyInfoId = 'KeyInfo-' . $suffix;
		$referenceId = 'Reference-' . $suffix;
		$signedPropertiesId = 'SignedProperties-' . $suffix;

		$signatureEl = $doc->createElementNS(self::NS_DS, 'ds:Signature');
		$signatureEl->setAttribute('Id', $signatureId);
		$root->appendChild($signatureEl);

		$signedInfoEl = $doc->createElementNS(self::NS_DS, 'ds:SignedInfo');
		$signedInfoEl->setAttribute('Id', $signedInfoId);
		$signatureEl->appendChild($signedInfoEl);

		$this->appendEl($doc, $signedInfoEl, self::NS_DS, 'ds:CanonicalizationMethod')
			->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
		$this->appendEl($doc, $signedInfoEl, self::NS_DS, 'ds:SignatureMethod')
			->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256');

		// Reference 1: the whole document.
		$documentReferenceEl = $doc->createElementNS(self::NS_DS, 'ds:Reference');
		$documentReferenceEl->setAttribute('Id', $referenceId);
		$documentReferenceEl->setAttribute('URI', '');
		$signedInfoEl->appendChild($documentReferenceEl);

		$transformsEl = $this->appendEl($doc, $documentReferenceEl, self::NS_DS, 'ds:Transforms');
		$this->appendEl($doc, $transformsEl, self::NS_DS, 'ds:Transform')
			->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');
		$this->appendEl($doc, $transformsEl, self::NS_DS, 'ds:Transform')
			->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
		$this->appendEl($doc, $documentReferenceEl, self::NS_DS, 'ds:DigestMethod')
			->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');
		$this->appendEl($doc, $documentReferenceEl, self::NS_DS, 'ds:DigestValue', $documentDigest);

		// Object / QualifyingProperties / SignedProperties (XAdES), attached now so its digest
		// reflects the namespaces it actually inherits in its final position in the document.
		$objectEl = $doc->createElementNS(self::NS_DS, 'ds:Object');
		$signatureEl->appendChild($objectEl);

		$qualifyingPropertiesEl = $doc->createElementNS(self::NS_XADES, 'xades:QualifyingProperties');
		$qualifyingPropertiesEl->setAttribute('Target', '#' . $signatureId);
		$objectEl->appendChild($qualifyingPropertiesEl);

		$signedPropertiesEl = $doc->createElementNS(self::NS_XADES, 'xades:SignedProperties');
		$signedPropertiesEl->setAttribute('Id', $signedPropertiesId);
		$qualifyingPropertiesEl->appendChild($signedPropertiesEl);

		$signedSignaturePropertiesEl = $this->appendEl($doc, $signedPropertiesEl, self::NS_XADES, 'xades:SignedSignatureProperties');
		$this->appendEl($doc, $signedSignaturePropertiesEl, self::NS_XADES, 'xades:SigningTime', date('c'));

		$signingCertificateEl = $this->appendEl($doc, $signedSignaturePropertiesEl, self::NS_XADES, 'xades:SigningCertificate');
		$certEl = $this->appendEl($doc, $signingCertificateEl, self::NS_XADES, 'xades:Cert');
		$certDigestEl = $this->appendEl($doc, $certEl, self::NS_XADES, 'xades:CertDigest');
		$this->appendEl($doc, $certDigestEl, self::NS_DS, 'ds:DigestMethod')
			->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');
		$this->appendEl($doc, $certDigestEl, self::NS_DS, 'ds:DigestValue', base64_encode(hash('sha256', $certDer, true)));
		$issuerSerialEl = $this->appendEl($doc, $certEl, self::NS_XADES, 'xades:IssuerSerial');
		$this->appendEl($doc, $issuerSerialEl, self::NS_DS, 'ds:X509IssuerName', $this->formatIssuerDn($certInfo));
		$this->appendEl($doc, $issuerSerialEl, self::NS_DS, 'ds:X509SerialNumber', $this->formatSerial($certInfo));

		$policyIdentifierEl = $this->appendEl($doc, $signedSignaturePropertiesEl, self::NS_XADES, 'xades:SignaturePolicyIdentifier');
		$policyIdEl = $this->appendEl($doc, $policyIdentifierEl, self::NS_XADES, 'xades:SignaturePolicyId');
		$sigPolicyIdEl = $this->appendEl($doc, $policyIdEl, self::NS_XADES, 'xades:SigPolicyId');
		$this->appendEl($doc, $sigPolicyIdEl, self::NS_XADES, 'xades:Identifier', self::POLICY_IDENTIFIER);
		$this->appendEl($doc, $sigPolicyIdEl, self::NS_XADES, 'xades:Description', self::POLICY_DESCRIPTION);
		$sigPolicyHashEl = $this->appendEl($doc, $policyIdEl, self::NS_XADES, 'xades:SigPolicyHash');
		$this->appendEl($doc, $sigPolicyHashEl, self::NS_DS, 'ds:DigestMethod')
			->setAttribute('Algorithm', self::POLICY_DIGEST_METHOD);
		$this->appendEl($doc, $sigPolicyHashEl, self::NS_DS, 'ds:DigestValue', self::POLICY_DIGEST_VALUE);

		if ($claimedRole) {
			$signerRoleEl = $this->appendEl($doc, $signedSignaturePropertiesEl, self::NS_XADES, 'xades:SignerRole');
			$claimedRolesEl = $this->appendEl($doc, $signerRoleEl, self::NS_XADES, 'xades:ClaimedRoles');
			$this->appendEl($doc, $claimedRolesEl, self::NS_XADES, 'xades:ClaimedRole', $claimedRole);
		}

		$signedDataObjectPropertiesEl = $this->appendEl($doc, $signedPropertiesEl, self::NS_XADES, 'xades:SignedDataObjectProperties');
		$dataObjectFormatEl = $this->appendEl($doc, $signedDataObjectPropertiesEl, self::NS_XADES, 'xades:DataObjectFormat');
		$dataObjectFormatEl->setAttribute('ObjectReference', '#' . $referenceId);
		$this->appendEl($doc, $dataObjectFormatEl, self::NS_XADES, 'xades:Description', 'Factura electronica');
		$this->appendEl($doc, $dataObjectFormatEl, self::NS_XADES, 'xades:MimeType', 'text/xml');

		// SignedProperties is now complete and in its final position: digest it and reference it.
		$signedPropertiesDigest = base64_encode(hash('sha256', $signedPropertiesEl->C14N(false, false), true));

		$signedPropertiesReferenceEl = $doc->createElementNS(self::NS_DS, 'ds:Reference');
		$signedPropertiesReferenceEl->setAttribute('Type', 'http://uri.etsi.org/01903#SignedProperties');
		$signedPropertiesReferenceEl->setAttribute('URI', '#' . $signedPropertiesId);
		$signedInfoEl->appendChild($signedPropertiesReferenceEl);
		$this->appendEl($doc, $signedPropertiesReferenceEl, self::NS_DS, 'ds:DigestMethod')
			->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');
		$this->appendEl($doc, $signedPropertiesReferenceEl, self::NS_DS, 'ds:DigestValue', $signedPropertiesDigest);

		// SignedInfo is now complete: canonicalize and sign it.
		$signatureValueRaw = '';
		$signed = openssl_sign($signedInfoEl->C14N(false, false), $signatureValueRaw, $privateKey, OPENSSL_ALGO_SHA256);

		if (!$signed) {
			throw new FacturaeSignerException('No se pudo firmar la factura: ' . openssl_error_string());
		}

		$signatureValueEl = $doc->createElementNS(self::NS_DS, 'ds:SignatureValue');
		$signatureValueEl->setAttribute('Id', $signatureValueId);
		$signatureValueEl->appendChild($doc->createTextNode(base64_encode($signatureValueRaw)));
		$signatureEl->insertBefore($signatureValueEl, $objectEl);

		$keyInfoEl = $doc->createElementNS(self::NS_DS, 'ds:KeyInfo');
		$keyInfoEl->setAttribute('Id', $keyInfoId);
		$x509DataEl = $this->appendEl($doc, $keyInfoEl, self::NS_DS, 'ds:X509Data');
		$this->appendEl($doc, $x509DataEl, self::NS_DS, 'ds:X509Certificate', base64_encode($certDer));
		$signatureEl->insertBefore($keyInfoEl, $objectEl);

		return $doc->saveXML();
	}

	private function loadCertificate($path, $password) {
		if (!is_file($path)) {
			throw new FacturaeSignerException('No se encuentra el fichero del certificado: ' . $path);
		}

		$raw = file_get_contents($path);
		$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

		if ($extension === 'p12' || $extension === 'pfx') {
			if (!openssl_pkcs12_read($raw, $certs, (string)$password)) {
				throw new FacturaeSignerException('No se pudo leer el certificado .p12/.pfx (revisa la contrasena)');
			}

			return array($certs['cert'], $certs['pkey']);
		}

		$certResource = openssl_x509_read($raw);

		if (!$certResource) {
			throw new FacturaeSignerException('No se pudo leer el certificado');
		}

		openssl_x509_export($certResource, $certPem);

		$keyResource = openssl_pkey_get_private($raw, (string)$password);

		if (!$keyResource) {
			throw new FacturaeSignerException('El certificado no incluye una clave privada utilizable (usa un .p12/.pfx, o un .pem con la clave incluida)');
		}

		openssl_pkey_export($keyResource, $keyPem);

		return array($certPem, $keyPem);
	}

	private function pemToDer($pem) {
		$body = preg_replace('/-----(BEGIN|END) CERTIFICATE-----/', '', $pem);
		$body = preg_replace('/\s+/', '', $body);

		return base64_decode($body);
	}

	private function formatIssuerDn($certInfo) {
		$parts = array();

		foreach ($certInfo['issuer'] as $key => $value) {
			foreach ((array)$value as $single) {
				$parts[] = $key . '=' . str_replace(array(',', '='), array('\,', '\='), $single);
			}
		}

		return implode(',', array_reverse($parts));
	}

	private function formatSerial($certInfo) {
		return isset($certInfo['serialNumber']) ? (string)$certInfo['serialNumber'] : '0';
	}

	private function appendEl(DOMDocument $doc, DOMElement $parent, $namespace, $name, $text = null) {
		$el = $doc->createElementNS($namespace, $name);

		if ($text !== null) {
			$el->appendChild($doc->createTextNode($text));
		}

		$parent->appendChild($el);

		return $el;
	}

	private function uniqueSuffix() {
		return strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 12));
	}
}

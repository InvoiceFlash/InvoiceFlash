<?php
final class Encryption {
	private $key;

	public function __construct($key) {
		$this->key = hash('sha256', $key, true);
	}

	public function encrypt($value) {
		return strtr(base64_encode(openssl_encrypt($value, 'aes-256-ecb', hash('sha256', $this->key, true), OPENSSL_RAW_DATA)), '+/=', '-_,');
	}

	public function decrypt($value) {
		return trim(openssl_decrypt(base64_decode(strtr($value, '-_,', '+/=')), 'aes-256-ecb', hash('sha256', $this->key, true), OPENSSL_RAW_DATA));
	}
}
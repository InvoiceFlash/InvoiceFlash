<?php
class ModelToolUpgrade extends Model {
	const REPO = 'InvoiceFlash/InvoiceFlash';
	const CHECK_INTERVAL = 86400;

	private $error = '';
	private $httpError = '';
	private $branchCache = null;

	public function getStatus() {
		$last_check = (int)$this->config->get('config_update_last_check');
		$latest_commit = $this->config->get('config_update_latest_commit');

		// Retry regardless of CHECK_INTERVAL whenever we still don't have a
		// result: otherwise a single failed check (no network, GitHub
		// unreachable, branch just renamed) gets cached as "no data" for a
		// full day even after whatever caused it is fixed.
		if (!$last_check || !$latest_commit || ((time() - $last_check) > self::CHECK_INTERVAL)) {
			$this->check();
		}

		return array(
			'current_commit' => $this->config->get('config_update_current_commit'),
			'latest_commit'  => $this->config->get('config_update_latest_commit'),
			'latest_message' => $this->config->get('config_update_latest_message'),
			'latest_date'    => $this->config->get('config_update_latest_date'),
			'last_check'     => $this->config->get('config_update_last_check'),
			'branch'         => $this->getBranch(),
			'error'          => $this->error
		);
	}

	public function check() {
		$branch = $this->getBranch();

		$values = array('config_update_last_check' => time());

		if (!$branch) {
			if (!$this->error) {
				$this->error = 'No se ha podido determinar la rama por defecto del repositorio en GitHub.' . ($this->httpError ? ' (' . $this->httpError . ')' : '');
			}

			$this->save($values);

			return $values;
		}

		$data = $this->apiRequest('https://api.github.com/repos/' . self::REPO . '/commits/' . $branch);

		if ($data && !empty($data['sha'])) {
			$values['config_update_latest_commit'] = $data['sha'];
			$values['config_update_latest_message'] = isset($data['commit']['message']) ? $data['commit']['message'] : '';
			$values['config_update_latest_date'] = isset($data['commit']['committer']['date']) ? $data['commit']['committer']['date'] : '';

			// First check ever: there is no way to know which commit the
			// files on this server actually correspond to, so the commit we
			// just saw becomes the baseline. Only commits pushed to the
			// default branch after this point will be reported as an
			// available update.
			if (!$this->config->get('config_update_current_commit')) {
				$values['config_update_current_commit'] = $data['sha'];
			}
		} elseif (!$this->error) {
			$this->error = 'No se ha podido consultar el ultimo commit de "' . $branch . '" en GitHub.' . ($this->httpError ? ' (' . $this->httpError . ')' : '');
		}

		$this->save($values);

		return $values;
	}

	public function getCompareUrl($status) {
		if ($status['current_commit'] && $status['latest_commit'] && ($status['current_commit'] != $status['latest_commit'])) {
			return 'https://github.com/' . self::REPO . '/compare/' . $status['current_commit'] . '...' . $status['latest_commit'];
		}

		return 'https://github.com/' . self::REPO . '/commits/' . ($status['branch'] ? $status['branch'] : 'HEAD');
	}

	// The default branch gets renamed with every release (e.g.
	// InvoiceFlash-0.0.7), so it must be resolved from the GitHub API
	// rather than hardcoded, or checks silently stop working after
	// each rename.
	private function getBranch() {
		// Memoized per request: getStatus() and check() both resolve the
		// branch, and on a network failure that would otherwise mean the
		// same timed-out request firing 2-3 times per page load/click.
		if ($this->branchCache !== null) {
			return $this->branchCache;
		}

		$branch = $this->config->get('config_update_branch');
		$fetched_at = (int)$this->config->get('config_update_branch_time');

		if ($branch && $fetched_at && ((time() - $fetched_at) < self::CHECK_INTERVAL)) {
			return $this->branchCache = $branch;
		}

		$data = $this->apiRequest('https://api.github.com/repos/' . self::REPO);

		if ($data && !empty($data['default_branch'])) {
			$this->save(array(
				'config_update_branch'      => $data['default_branch'],
				'config_update_branch_time' => time()
			));

			return $this->branchCache = $data['default_branch'];
		}

		return $this->branchCache = $branch;
	}

	// Downloads the repository's default branch as a zip, overlays it on
	// top of the current installation (skipping install/ and php.ini) and
	// keeps a zip backup of every file it replaces under system/backup/.
	// config.php is never touched: it is excluded from the repository
	// itself, so it is never present in the downloaded zip.
	public function upgrade() {
		if (!function_exists('curl_init')) {
			return array('success' => false, 'error' => 'cURL no esta disponible en este servidor.');
		}

		if (!class_exists('ZipArchive')) {
			return array('success' => false, 'error' => 'La extension ZipArchive de PHP no esta disponible en este servidor.');
		}

		$status = $this->getStatus();

		if (!$status['branch']) {
			return array('success' => false, 'error' => 'No se ha podido determinar la rama por defecto del repositorio.');
		}

		if (!$status['latest_commit']) {
			return array('success' => false, 'error' => 'No se ha podido comprobar la ultima version disponible.');
		}

		if (!is_dir(DIR_CACHE)) {
			mkdir(DIR_CACHE, 0777, true);
		}

		$tmp_zip = DIR_CACHE . 'update_' . uniqid() . '.zip';
		$tmp_dir = DIR_CACHE . 'update_' . uniqid() . '/';

		if (!$this->download('https://github.com/' . self::REPO . '/archive/refs/heads/' . $status['branch'] . '.zip', $tmp_zip)) {
			return array('success' => false, 'error' => 'No se ha podido descargar la actualizacion desde GitHub.');
		}

		$zip = new ZipArchive();

		if ($zip->open($tmp_zip) !== true) {
			unlink($tmp_zip);

			return array('success' => false, 'error' => 'El archivo descargado no es un zip valido.');
		}

		mkdir($tmp_dir, 0777, true);

		$zip->extractTo($tmp_dir);
		$zip->close();

		unlink($tmp_zip);

		$extracted_root = $this->findExtractedRoot($tmp_dir);

		if (!$extracted_root) {
			$this->removeDirectory($tmp_dir);

			return array('success' => false, 'error' => 'No se ha podido localizar el contenido descargado.');
		}

		$root = realpath(DIR_SYSTEM . '..') . '/';

		if (!is_dir(DIR_SYSTEM . 'backup')) {
			mkdir(DIR_SYSTEM . 'backup', 0777, true);
		}

		$backup_file = DIR_SYSTEM . 'backup/backup_' . date('Y-m-d_H-i-s') . '.zip';

		$backup_zip = new ZipArchive();
		$backup_zip->open($backup_file, ZipArchive::CREATE);

		$this->copyDirectory($extracted_root, $root, $backup_zip, '');

		$backup_zip->close();

		$this->removeDirectory($tmp_dir);

		$this->save(array(
			'config_update_current_commit' => $status['latest_commit'],
			'config_update_last_upgrade'   => time()
		));

		return array('success' => true, 'backup' => $backup_file);
	}

	private function findExtractedRoot($dir) {
		$items = array_diff(scandir($dir), array('.', '..'));

		foreach ($items as $item) {
			if (is_dir($dir . $item)) {
				return $dir . $item . '/';
			}
		}

		return false;
	}

	private function copyDirectory($source, $destination, $backup_zip, $relative) {
		$skip = array('install', 'php.ini');

		$items = array_diff(scandir($source), array('.', '..'));

		foreach ($items as $item) {
			$rel_path = $relative ? $relative . '/' . $item : $item;

			if (in_array($rel_path, $skip)) {
				continue;
			}

			$source_path = $source . $item;
			$destination_path = $destination . $rel_path;

			if (is_dir($source_path)) {
				if (!is_dir($destination_path)) {
					mkdir($destination_path, 0777, true);
				}

				$this->copyDirectory($source_path . '/', $destination, $backup_zip, $rel_path);
			} else {
				if (file_exists($destination_path)) {
					$backup_zip->addFile($destination_path, $rel_path);
				}

				copy($source_path, $destination_path);
			}
		}
	}

	private function removeDirectory($dir) {
		if (!is_dir($dir)) {
			return;
		}

		$items = array_diff(scandir($dir), array('.', '..'));

		foreach ($items as $item) {
			$path = $dir . $item;

			if (is_dir($path)) {
				$this->removeDirectory($path . '/');
			} else {
				unlink($path);
			}
		}

		rmdir($dir);
	}

	private function apiRequest($url) {
		$response = $this->httpRequest($url, array('User-Agent: InvoiceFlash-Update-Check', 'Accept: application/vnd.github+json'));

		if (!$response) {
			return false;
		}

		$data = json_decode($response, true);

		return is_array($data) ? $data : false;
	}

	// Some PHP installs (stock Windows XAMPP among others) ship without a
	// working CA bundle, so cURL's SSL verification fails with "unable to
	// get local issuer certificate" even though the connection itself is
	// fine. Point cURL at our own bundled copy instead of disabling
	// verification.
	private function caBundle() {
		$path = DIR_SYSTEM . 'cacert.pem';

		return is_file($path) ? $path : null;
	}

	private function download($url, $destination) {
		$fp = fopen($destination, 'w');

		if (!$fp) {
			return false;
		}

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_USERAGENT, 'InvoiceFlash-Update');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		$ca_bundle = $this->caBundle();

		if ($ca_bundle) {
			curl_setopt($ch, CURLOPT_CAINFO, $ca_bundle);
		}

		curl_exec($ch);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$failed = curl_errno($ch) || ($http_code >= 400);

		curl_close($ch);
		fclose($fp);

		if ($failed) {
			if (file_exists($destination)) {
				unlink($destination);
			}

			return false;
		}

		return true;
	}

	private function httpRequest($url, $headers = array()) {
		if (!function_exists('curl_init')) {
			$this->httpError = 'La extension cURL de PHP no esta habilitada en este servidor.';

			return false;
		}

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 8);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		$ca_bundle = $this->caBundle();

		if ($ca_bundle) {
			curl_setopt($ch, CURLOPT_CAINFO, $ca_bundle);
		}

		if ($headers) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		$response = curl_exec($ch);
		$errno = curl_errno($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($errno) {
			$this->httpError = 'cURL error ' . $errno . ': ' . curl_error($ch);
		} elseif (!$response) {
			$this->httpError = 'El servidor de GitHub no devolvio ninguna respuesta (HTTP ' . $http_code . ').';
		}

		$failed = $errno || !$response;

		curl_close($ch);

		return $failed ? false : $response;
	}

	private function save($data) {
		foreach ($data as $key => $value) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `key` = '" . $this->db->escape($key) . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'upgrade', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");

			$this->config->set($key, $value);
		}
	}
}
?>

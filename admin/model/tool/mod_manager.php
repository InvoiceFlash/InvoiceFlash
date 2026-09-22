<?php
class ModelToolModManager extends Model {

	public function getMods() {
		$mods = array();

		$xml_dir = DIR_SYSTEM . '../vqmod/xml/';

		$files = array_merge(
			glob($xml_dir . '*.xml'),
			glob($xml_dir . '*.xml.disabled')
		);

		if (!$files) {
			return $mods;
		}

		sort($files);

		foreach ($files as $file) {
			$basename = basename($file);
			$enabled = (substr($basename, -13) != '.xml.disabled');

			$info = $this->parseModInfo($file);

			$mods[] = array(
				'file'    => $basename,
				'id'      => $info['id'],
				'version' => $info['version'],
				'author'  => $info['author'],
				'enabled' => $enabled
			);
		}

		return $mods;
	}

	private function parseModInfo($file) {
		$info = array(
			'id'      => basename($file),
			'version' => '',
			'author'  => ''
		);

		$content = file_get_contents($file);

		if ($content === false) {
			return $info;
		}

		libxml_use_internal_errors(true);

		$xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NONET);

		libxml_clear_errors();

		if ($xml !== false) {
			if (isset($xml->id) && trim((string)$xml->id) !== '') {
				$info['id'] = trim((string)$xml->id);
			}

			if (isset($xml->version)) {
				$info['version'] = trim((string)$xml->version);
			}

			if (isset($xml->author)) {
				$info['author'] = trim((string)$xml->author);
			}
		}

		return $info;
	}

	public function toggleMod($filename) {
		$xml_dir = DIR_SYSTEM . '../vqmod/xml/';

		$path = realpath($xml_dir . $filename);

		if (!$path || strpos($path, realpath($xml_dir)) !== 0 || !is_file($path)) {
			return false;
		}

		if (substr($path, -13) == '.xml.disabled') {
			$new_path = substr($path, 0, -9);
		} elseif (substr($path, -4) == '.xml') {
			$new_path = $path . '.disabled';
		} else {
			return false;
		}

		if (file_exists($new_path)) {
			return false;
		}

		return rename($path, $new_path);
	}

	public function clearCache() {
		$vqmod_dir = DIR_SYSTEM . '../vqmod/';

		$cache_files = glob($vqmod_dir . 'vqcache/vq2-*.php');

		if ($cache_files) {
			foreach ($cache_files as $cache_file) {
				@unlink($cache_file);
			}
		}

		if (is_file($vqmod_dir . 'mods.cache')) {
			@unlink($vqmod_dir . 'mods.cache');
		}

		if (is_file($vqmod_dir . 'checked.cache')) {
			@unlink($vqmod_dir . 'checked.cache');
		}

		return true;
	}
}

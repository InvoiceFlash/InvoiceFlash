<?php
class ModelSettingDocumentEmbeddings extends Model {
	public function getDocsDir() {
		$project_root = rtrim(str_replace('\\', '/', dirname(DIR_APPLICATION)), '/');

		return $project_root . '/docs/products/';
	}

	public function getDocuments() {
		$dir = $this->getDocsDir();

		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}

		$files = glob($dir . '*.pdf');

		$documents = array();

		if ($files) {
			natcasesort($files);

			foreach ($files as $path) {
				$filename = basename($path);
				$document_id = pathinfo($filename, PATHINFO_FILENAME);

				$product = $this->findProduct($document_id);

				$log = $this->getLog($document_id);

				$documents[] = array(
					'filename'      => $filename,
					'document_id'   => $document_id,
					'product_id'    => $product ? $product['product_id'] : ($log ? $log['product_id'] : null),
					'product_name'  => $product ? $product['name'] : ($log ? $log['product_name'] : null),
					'status'        => $log ? $log['status'] : 'pending',
					'pages'         => $log ? (int)$log['pages'] : 0,
					'chunks'        => $log ? (int)$log['chunks'] : 0,
					'error_message' => $log ? $log['error_message'] : '',
					'date_finished' => ($log && $log['date_finished']) ? date('d-m-Y H:i', strtotime($log['date_finished'])) : '',
				);
			}
		}

		return $documents;
	}

	private function findProduct($document_id) {
		$query = $this->db->query("
			SELECT p.product_id, pd.name
			FROM `" . DB_PREFIX . "product` p
			LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = p.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')
			WHERE p.sku = '" . $this->db->escape($document_id) . "' OR p.model = '" . $this->db->escape($document_id) . "'
			LIMIT 1
		");

		if (!$query->num_rows) {
			return null;
		}

		if (!$query->row['name']) {
			$fallback = $this->db->query("SELECT name FROM `" . DB_PREFIX . "product_description` WHERE product_id = '" . (int)$query->row['product_id'] . "' LIMIT 1");

			if ($fallback->num_rows) {
				$query->row['name'] = $fallback->row['name'];
			}
		}

		return $query->row;
	}

	private function getLog($document_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "document_embedding_log` WHERE document_id = '" . $this->db->escape($document_id) . "' LIMIT 1");

		return $query->num_rows ? $query->row : null;
	}
}

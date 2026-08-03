<?php
class ModelToolsReportDesigner extends Model {

	private $types = array('quote', 'order', 'delivery', 'invoice');

	// Nombre de la variable de $this->data que trae la lista de documentos a
	// imprimir (ver el foreach de cada *_printPDF.tpl original) - hace falta
	// para extraer la fila real en buildMergeTags().
	private $row_list_key = array(
		'quote'    => 'quotes',
		'order'    => 'orders',
		'delivery' => 'deliveries',
		'invoice'  => 'invoices'
	);

	public function isValidType($type) {
		return in_array($type, $this->types);
	}

	public function getTypes() {
		return $this->types;
	}

	public function getFormats($type) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "report_format` WHERE type = '" . $this->db->escape($type) . "' ORDER BY is_default DESC, name ASC");

		return $query->rows;
	}

	public function getFormat($report_format_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "report_format` WHERE report_format_id = '" . (int)$report_format_id . "'");

		return $query->row;
	}

	public function getDefaultFormat($type) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "report_format` WHERE type = '" . $this->db->escape($type) . "' AND is_default = '1'");

		return $query->row;
	}

	public function addFormat($type, $name, $html_content, $user_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "report_format` SET
			type          = '" . $this->db->escape($type) . "',
			name          = '" . $this->db->escape($name) . "',
			html_content  = '" . $this->db->escape($html_content) . "',
			is_default    = '0',
			user_id       = '" . (int)$user_id . "',
			date_added    = NOW()");

		return $this->db->getLastId();
	}

	// Solo toca formatos no is_default: la fila de fabrica nunca se edita.
	public function editFormatHtml($report_format_id, $html_content) {
		$this->db->query("UPDATE `" . DB_PREFIX . "report_format` SET html_content = '" . $this->db->escape($html_content) . "' WHERE report_format_id = '" . (int)$report_format_id . "' AND is_default = '0'");
	}

	public function deleteFormat($report_format_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "report_format` WHERE report_format_id = '" . (int)$report_format_id . "' AND is_default = '0'");
	}

	public function getActiveFormatId($type) {
		$this->load->model('setting/setting');

		$active = $this->model_setting_setting->getSetting('report_format_active');

		return !empty($active[$type]) ? (int)$active[$type] : 0;
	}

	// getSetting()/editSetting() operan sobre el grupo entero: hay que leer
	// el grupo completo, cambiar solo la clave de este tipo, y reescribirlo
	// entero - si no, editSetting() borraria los otros 3 tipos ya activados
	// (mismo aviso que ya deja tools/invoice_design.php sobre el grupo 'config').
	public function setActiveFormat($type, $report_format_id) {
		$this->load->model('setting/setting');

		$active = $this->model_setting_setting->getSetting('report_format_active');
		$active[$type] = (int)$report_format_id;

		$this->model_setting_setting->editSetting('report_format_active', $active);
	}

	// Ultimo documento real de este tipo (para "Vista previa PDF"), o 0 si
	// todavia no hay ninguno.
	public function getLatestDocumentId($type) {
		$table_column = array(
			'quote'    => array(DB_PREFIX . 'quote', 'quote_id'),
			'order'    => array(DB_PREFIX . 'order', 'order_id'),
			'delivery' => array(DB_PREFIX . 'delivery', 'delivery_id'),
			'invoice'  => array(DB_PREFIX . 'invoice', 'invoice_id')
		);

		if (!isset($table_column[$type])) {
			return 0;
		}

		list($table, $column) = $table_column[$type];

		$query = $this->db->query("SELECT MAX(`" . $column . "`) AS max_id FROM `" . $table . "`");

		return (int)$query->row['max_id'];
	}

	// Request::clean() (system/library/request.php) pasa htmlspecialchars()
	// sobre TODO $_GET/$_POST, asi que el HTML que postea CKEditor llega con
	// sus etiquetas convertidas en &lt;p&gt;... - hay que deshacer ese escape
	// antes de guardarlo, o se guardaria como texto escapado en vez de HTML.
	public function decodeHtmlInput($raw) {
		return is_string($raw) ? html_entity_decode($raw, ENT_COMPAT, 'UTF-8') : '';
	}

	// Si hay un formato activo (no el de fabrica) para este tipo con
	// contenido propio, devuelve un array de fragmentos HTML ya con los
	// marcadores {tag} sustituidos por los datos reales - uno por cada
	// documento que se este imprimiendo (soporta impresion masiva por
	// checkboxes, no solo un documento). Devuelve false si hay que usar la
	// plantilla original de siempre.
	public function getRenderableCustomHtml($type, $data) {
		$active_id = $this->getActiveFormatId($type);

		if (!$active_id) {
			return false;
		}

		$format = $this->getFormat($active_id);

		if (!$format || $format['type'] != $type || $format['is_default']) {
			return false;
		}

		return $this->renderPagesForFormat($format, $type, $data);
	}

	// Vista previa de un formato CONCRETO (activo o no, is_default o no) -
	// usado por tools/report_designer/preview para poder previsualizar antes
	// de activarlo.
	public function getPreviewHtml($report_format_id, $type, $data) {
		$format = $this->getFormat($report_format_id);

		if (!$format || $format['type'] != $type) {
			return false;
		}

		return $this->renderPagesForFormat($format, $type, $data);
	}

	private function renderPagesForFormat($format, $type, $data) {
		if (empty($format['html_content'])) {
			return false;
		}

		$list_key = $this->row_list_key[$type];
		$rows = !empty($data[$list_key]) ? $data[$list_key] : array();

		if (empty($rows)) {
			return false;
		}

		$pages = array();

		foreach ($rows as $row) {
			$tags = $this->buildMergeTags($type, $row, $data);
			$pages[] = $this->getBaseCss() . strtr($format['html_content'], $tags);
		}

		return $pages;
	}

	public function getMergeTagKeys($type) {
		$common = array('logo', 'store_name', 'store_address', 'store_telephone', 'store_fax', 'store_email', 'store_url', 'store_nif', 'date_added', 'document_number', 'payment_method', 'shipping_method', 'customer_address', 'customer_email', 'customer_telephone', 'customer_company_id', 'customer_tax_id', 'product_table', 'comment_block');

		if ($type == 'invoice') {
			$common[] = 'payment_company';
			$common[] = 'qr_code';
		} else {
			$common[] = 'shipping_address';
		}

		return $common;
	}

	// Construye el mapa {marcador} => valor real para UNA fila de documento.
	// $data es el $this->data del controller de venta (trae los textos de
	// idioma column_*/text_* y el logo); $row es la fila concreta (una
	// posicion de $data['quotes']/['orders']/['deliveries']/['invoices']).
	private function buildMergeTags($type, $row, $data) {
		$get = function ($key) use ($row) {
			return isset($row[$key]) ? $row[$key] : '';
		};

		$tags = array(
			'{logo}'                 => !empty($data['logo']) ? '<img src="../image/' . $data['logo'] . '" style="width:24mm;" />' : '',
			'{store_name}'           => $get('store_name'),
			'{store_address}'        => $get('store_address'),
			'{store_telephone}'      => $get('store_telephone'),
			'{store_fax}'            => $get('store_fax'),
			'{store_email}'          => $get('store_email'),
			'{store_url}'            => $get('store_url'),
			'{store_nif}'            => $get('store_nif'),
			'{date_added}'           => $get('date_added'),
			'{payment_method}'       => $get('payment_method'),
			'{shipping_method}'      => $get('shipping_method'),
			'{customer_address}'     => $get('payment_address'),
			'{customer_email}'       => $get('email'),
			'{customer_telephone}'   => $get('telephone'),
			'{customer_company_id}' => $get('payment_company_id'),
			'{customer_tax_id}'      => $get('payment_tax_id'),
			'{product_table}'        => $this->renderProductTableHtml($type, $row, $data),
			'{comment_block}'        => $this->renderCommentBlockHtml($get('comment'), $data)
		);

		if ($type == 'quote') {
			$tags['{document_number}'] = $get('quote_no');
		} elseif ($type == 'order') {
			$tags['{document_number}'] = $get('order_id');
			$tags['{shipping_address}'] = $get('shipping_address');
		} elseif ($type == 'delivery') {
			$tags['{document_number}'] = $get('invoice_prefix') . $get('delivery_id');
			$tags['{shipping_address}'] = $get('shipping_address');
		} elseif ($type == 'invoice') {
			$tags['{document_number}'] = $get('invoice_prefix') . $get('invoice_id');
			$tags['{payment_company}'] = $get('payment_company');
			$tags['{qr_code}'] = $get('qr_code_pdf') ? '<img src="' . $get('qr_code_pdf') . '" style="width:30mm; height:30mm;" />' : '';
		}

		if ($type == 'quote') {
			$tags['{shipping_address}'] = $get('shipping_address');
		}

		return $tags;
	}

	// renderPDFFromHtml() manda html_content directo a TCPDF sin pasar por
	// ningun *_printPDF.tpl (que es de donde salian normalmente estas
	// reglas) - hay que inyectarlas aparte para que .table/.table-bordered/
	// .title sigan funcionando. Es fijo (no editable desde CKEditor): asi el
	// usuario no puede romperlo sin querer, y el formato solo controla
	// estructura/texto, no la hoja de estilos.
	private function getBaseCss() {
		return '<style>
* { padding: 0; margin: 0; }
.center { text-align: center; }
.right { text-align: right; }
.title { font-size: 28px; text-transform: uppercase; padding-left: 20px; text-align: right; }
.table-bordered { border: 1px solid grey; }
.table { width: 100%; margin-bottom: 1rem; background-color: white; margin: 5px; padding: 5px; }
th { font-weight: bold; background-color: #dee2e6; }
</style>';
	}

	// Tabla de lineas de producto + totales, igual que la generaba cada
	// *_printPDF.tpl original (columna 2 distinta por tipo: quote muestra las
	// opciones ahi, order/delivery el modelo, invoice no tiene esa columna).
	private function renderProductTableHtml($type, $row, $data) {
		$products = !empty($row['product']) ? $row['product'] : array();
		$totals = !empty($row['total']) ? $row['total'] : array();

		$html = '<table class="table table-bordered"><tr><th>' . $data['column_product'] . '</th>';

		if ($type == 'quote') {
			$html .= '<th>' . $data['column_delivery_date'] . '</th>';
		} elseif ($type == 'order' || $type == 'delivery') {
			$html .= '<th>' . $data['column_model'] . '</th>';
		}

		$html .= '<th class="center">' . $data['column_quantity'] . '</th>';
		$html .= '<th class="right">' . $data['column_price'] . '</th>';
		$html .= '<th class="right">' . $data['column_total'] . '</th></tr>';

		$colspan = ($type == 'invoice') ? 3 : 4;

		foreach ($products as $product) {
			$html .= '<tr><td>' . $product['name'];

			if ($type != 'quote') {
				foreach ((!empty($product['option']) ? $product['option'] : array()) as $option) {
					$html .= '<br>&nbsp;<small> - ' . $option['name'] . ': ' . $option['value'] . '</small>';
				}
			}

			$html .= '</td>';

			if ($type == 'quote') {
				$html .= '<td>';
				foreach ((!empty($product['option']) ? $product['option'] : array()) as $option) {
					$html .= '<div>' . $option['value'] . '</div>';
				}
				$html .= '</td>';
			} elseif ($type == 'order' || $type == 'delivery') {
				$html .= '<td>' . $product['model'] . '</td>';
			}

			$html .= '<td class="center">' . $product['quantity'] . '</td>';
			$html .= '<td class="right">' . $product['price'] . '</td>';
			$html .= '<td class="right">' . $product['total'] . '</td></tr>';
		}

		foreach ($totals as $total) {
			$html .= '<tr><td class="right" colspan="' . $colspan . '"><b>' . $total['title'] . ':</b></td><td class="right">' . $total['text'] . '</td></tr>';
		}

		$html .= '</table>';

		return $html;
	}

	// Igual que el "if comment" condicional de las plantillas originales: si
	// no hay comentario, el bloque entero desaparece en vez de dejar una
	// tabla vacia con solo la cabecera.
	private function renderCommentBlockHtml($comment, $data) {
		if ($comment === '' || $comment === null) {
			return '';
		}

		return '<table class="table table-bordered"><tr><th>' . $data['column_comment'] . '</th></tr><tr><td>' . $comment . '</td></tr></table>';
	}
}
?>

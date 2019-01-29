<?php
class ControllerExtensionElfinder extends Controller {

	public $version = '2.3_E2.1.40n';
			
	public function index() {
		//$this->load->language('common/filemanager');
		$this->language->load('extension/elfinder');
		
		$this->data = array_merge($data, $this->language->load('extension/elfinder'));

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], true),
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/elfinder', 'token='.$this->session->data['token'], true),
		);

		$this->data['button_clear_image_cache'] = $this->language->get('button_clear_image_cache');
		
		$this->data['button_settings'] = $this->language->get('button_settings');
		
		$this->data['text_confirm_clear_cache'] = $this->language->get('text_confirm_clear_cache');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$this->data['token_name'] = 'token';

		$this->data['token'] = $this->session->data['token'];

		$this->data['settings'] = $this->url->link('extension/elfinder/connector', 'token='.$this->session->data['token'], true); // TODO

		$this->data['connector_url'] = $this->url->link('extension/elfinder/connector', 'token='.$this->session->data['token'], true);

		$this->data['version'] = !empty($this->version) ? $this->version : 0;
		$this->data['oc_version'] = VERSION;

		$this->template = 'extension/elfinder.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		
	}

	public function clear_image_cache() {
		$this->language->load('common/filemanager');
		$this->language->load('extension/elfinder');

		$json = array();

		$modify = $this->user->hasPermission('modify', 'extension/elfinder') && $this->user->hasPermission('modify', 'common/filemanager');

		if ($modify) {
			$imgfiles = glob(DIR_IMAGE . 'cache/*');

			if ($imgfiles) {
				foreach ($imgfiles as $imgfile) {
					$this->rmdir_recursive($imgfile);
				}
			}

			$json['message'] = $this->language->get('text_success_clear_cache');
			$json['success'] = 1;
		} else {
			$json['message'] = $this->language->get('error_permission');
			$json['error'] = 1;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getVersion() {
		return !empty($this->version) ? $this->version : 0;
	}

	private function rmdir_recursive($dirname) {
		if (file_exists($dirname)) {
			if (is_dir($dirname)) {
				$dir = opendir($dirname);

				while ($filename = readdir($dir)){
					if ($filename != "." && $filename != "..") {
						$file = $dirname . "/" . $filename;
						$this->rmdir_recursive($file);
					}
				}

				closedir($dir);
				rmdir($dirname);
			} else {
				@unlink($dirname);
			}
		}
	}

	public function connector() {
		if (!empty($_SESSION)) {
			session_write_close();
		}

		$settings = $this->config->get('module_elfinder_settings');

		$autoload_path = 'model/extension/elfinder/autoload.php';
		$autoload_full_path = DIR_APPLICATION . $autoload_path;

		if (!is_file($autoload_full_path)) {
			die('Error! File ' . $autoload_path . ' is absent!');
		}

		require $autoload_full_path;

		if (!empty($_SERVER['HTTPS'])) {
			$catalog_url = HTTPS_CATALOG;
		} else {
			$catalog_url = HTTP_CATALOG;
		}

		$modify = $this->user->hasPermission('modify', 'extension/elfinder') && $this->user->hasPermission('modify', 'common/filemanager');

		$disabled = array();

		if (!$modify) {
			$disabled = array('rename','edit','upload','mkfile','mkdir','rm','cut','copy','duplicate','archive');
		}

		$upload_allow = trim($this->config->get('config_file_mime_allowed'));

		if (empty($upload_allow)) {
			$upload_allow = "text/plain
image
application/zip
application/x-zip
application/x-zip-compressed
application/rar
application/x-rar
application/x-rar-compressed
application/octet-stream
application/vnd.ms-excel
application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
application/vnd.oasis.opendocument.spreadsheet
application/msword
application/vnd.openxmlformats-officedocument.wordprocessingml.document
application/vnd.oasis.opendocument.text
application/pdf
audio/mpeg
video/quicktime";
		}

		$upload_allow = html_entity_decode(str_replace(array("\n\r", "\r\n", "\r", "\n"), "\n", $upload_allow), ENT_QUOTES, 'UTF-8');
		$upload_allow = explode("\n", $upload_allow);

		//echo '<pre>'.__METHOD__.' ['.__LINE__.']: '; print_r($upload_allow); echo '</pre>';die();
		
		
		
		$opts = array(
			'debug' => true,
			'commonTempPath' => DIR_IMAGE . 'cache/data/.tmp',
			'uploadTempPath' => DIR_IMAGE . 'cache/data/.tmp',
			'bind' => array(
				'upload.presave' => array()
			),
			'plugins' => array(
			),
			'roots' => array(
				array(
					'driver'         => 'LocalFileSystem',
					'path'           => DIR_IMAGE . 'data',
					'URL'            => $catalog_url . 'image/data',
					'imgLib'         => 'auto', // auto, imagick, gd
					'tmbPath'        => DIR_IMAGE . 'cache/data/.thumbs',
					'tmbURL'         => $catalog_url . 'image/cache/data/.thumbs',
					'tmbCrop'        => false,
					'tmbSize'        => 100,
					'tmbBgColor'     => '#FFFFFF',
					'tmpPath'        => DIR_IMAGE . 'cache/data/.tmp',
					'mimeDetect'     => 'auto', // auto, internal, finfo, mime_content_type
					'uploadDeny'     => array('all'),
					//'uploadAllow'  => array('image', 'text/plain'),
					'uploadAllow'    => $upload_allow,
					'uploadOrder'    => array('deny', 'allow'),
					'accessControl'  => array($this, 'access'),
					'disabled'       => $disabled,
				),
			),
		);

		if (!empty($opts['commonTempPath']) && !is_dir($opts['commonTempPath'])) {
			mkdir($opts['commonTempPath'], 0777, true);
		}

		if (!empty($opts['uploadTempPath']) && !is_dir($opts['uploadTempPath'])) {
			mkdir($opts['uploadTempPath'], 0777, true);
		}

		if (!empty($opts['roots'])) {
			foreach ($opts['roots'] as $root) {
				if (!empty($root['tmpPath']) && !is_dir($root['tmpPath'])) {
					mkdir($root['tmpPath'], 0777, true);
				}
			}
		}

		if (is_file(DIR_IMAGE . 'data/elfinder_watermark.png')) {
			$opts['bind']['upload.presave'][] = 'Plugin.Watermark.onUpLoadPreSave';

			$opts['plugin']['Watermark'] = array(
				'enable'         => true,       // For control by volume driver
				'source'         => DIR_IMAGE . 'data/elfinder_watermark.png', // Path to Water mark image
				'marginRight'    => 20,         // Margin right pixel
				'marginBottom'   => 20,         // Margin bottom pixel
				'quality'        => 100,        // JPEG image save quality
				'transparency'   => 100,        // Water mark image transparency ( other than PNG )
				'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP, // Target image formats ( bit-field )
				'targetMinPixel' => 300,        // Target image minimum pixel size
				'interlace'      => IMG_GIF|IMG_JPG, // Set interlacebit image formats ( bit-field )
				'offDropWith'    => null        // To disable it if it is dropped with pressing the meta key
												// Alt: 8, Ctrl: 4, Meta: 2, Shift: 1 - sum of each value
												// In case of using any key, specify it as an array
			);
		}

		$connector = new elFinderConnector(new elFinder($opts));

		$connector->run();
	}

	/**
	 * Simple function to demonstrate how to control file access using "accessControl" callback.
	 * This method will disable accessing files/folders starting from '.' (dot)
	 *
	 * @param  string $attr attribute name (read|write|locked|hidden)
	 * @param  string $path file path relative to volume root directory started with directory separator
	 *
	 * @return bool|null
	 **/
	public function access($attr, $path, $data, $volume) {
		// if file/folder begins with '.' (dot)
		if (strpos(basename($path), '.') === 0) {
			// set read+write to false, other (locked+hidden) set to true
			return !($attr == 'read' || $attr == 'write');
		}

		// elFinder decide it itself
		return null;
	}
}
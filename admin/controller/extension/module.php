<?php
class ControllerExtensionModule extends Controller {
	public function index() {
		$this->language->load('extension/module');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_action'] = $this->language->get('column_action');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('module');

		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/module/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('module', $value);

				unset($extensions[$key]);
			}
		}

		$this->data['extensions'] = array();

		$files = glob(DIR_APPLICATION . 'controller/module/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->language->load('module/' . $extension);

				$action = array();

				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => $this->url->link('extension/module/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
					);
				} else {
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('module/' . $extension . '', 'token=' . $this->session->data['token'], 'SSL')
					);

					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => $this->url->link('extension/module/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
					);
				}

				$this->data['extensions'][] = array(
					'name'   => $this->language->get('heading_title'),
					'action' => $action
				);
			}
		}

		// VQMod XML modules (activos y desactivados)
		$vqmod_dir    = dirname(DIR_APPLICATION) . '/vqmod/xml/';
		$vqmod_ignore = array('vqmod_invoiceflash.xml');
		$vqmod_all    = array_merge(
			(array)glob($vqmod_dir . '*.xml'),
			(array)glob($vqmod_dir . '*.xml.disabled')
		);

		foreach ($vqmod_all as $vqmod_file) {
			$basename = basename($vqmod_file);

			if (in_array($basename, $vqmod_ignore) || in_array(str_replace('.disabled', '', $basename), $vqmod_ignore)) {
				continue;
			}

			$active = (substr($basename, -9) !== '.disabled');

			$xml = @simplexml_load_file($vqmod_file);
			if (!$xml) continue;

			$id      = isset($xml->id)      ? (string)$xml->id      : str_replace(array('.xml.disabled', '.xml'), '', $basename);
			$version = isset($xml->version)  ? (string)$xml->version : '';
			$author  = isset($xml->author)   ? (string)$xml->author  : '';

			$label = htmlspecialchars($id);
			if ($version) $label .= ' <small class="text-muted">v' . htmlspecialchars($version) . '</small>';
			if ($author)  $label .= ' <small class="text-muted">— ' . htmlspecialchars($author) . '</small>';

			if ($active) {
				$label .= ' <span class="badge" style="background:#28a745;color:#fff;padding:3px 7px;">VQMod activo</span>';
				$action = array(array(
					'text' => 'Desactivar',
					'href' => str_replace('&amp;', '&', $this->url->link('extension/module/disableVqmod', 'token=' . $this->session->data['token'] . '&file=' . urlencode($basename), 'SSL'))
				));
			} else {
				$label .= ' <span class="badge" style="background:#dc3545;color:#fff;padding:3px 7px;">VQMod inactivo</span>';
				$action = array(array(
					'text' => 'Activar',
					'href' => str_replace('&amp;', '&', $this->url->link('extension/module/enableVqmod', 'token=' . $this->session->data['token'] . '&file=' . urlencode($basename), 'SSL'))
				));
			}

			$this->data['extensions'][] = array(
				'name'   => $label,
				'action' => $action
			);
		}

		$this->template = 'extension/module.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function install() {
		$this->language->load('extension/module');

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->data['error'] = $this->language->get('error_permission'); 

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('module', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'module/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'module/' . $this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/module/' . $this->request->get['extension'] . '.php');

			$class = 'ControllerModule' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'install')) {
				$class->install();
			}

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function uninstall() {
		$this->language->load('extension/module');

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->data['error'] = $this->language->get('error_permission');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->load->model('setting/extension');
			$this->load->model('setting/setting');

			$this->model_setting_extension->uninstall('module', $this->request->get['extension']);

			$this->model_setting_setting->deleteSetting($this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/module/' . $this->request->get['extension'] . '.php');

			$class = 'ControllerModule' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function disableVqmod() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$file = basename(rawurldecode($this->request->get['file']));

		// Solo ficheros .xml activos, sin path traversal
		if (substr($file, -4) === '.xml' && strpos($file, '/') === false && strpos($file, '\\') === false) {
			$vqmod_dir = dirname(DIR_APPLICATION) . '/vqmod/xml/';
			$src = $vqmod_dir . $file;
			$dst = $src . '.disabled';

			if (file_exists($src)) {
				rename($src, $dst);
				$this->clearVqmodCache();
			}
		}

		$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function enableVqmod() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$file = basename(rawurldecode($this->request->get['file']));

		// Solo ficheros .xml.disabled, sin path traversal
		if (substr($file, -13) === '.xml.disabled' && strpos($file, '/') === false && strpos($file, '\\') === false) {
			$vqmod_dir = dirname(DIR_APPLICATION) . '/vqmod/xml/';
			$src = $vqmod_dir . $file;
			$dst = $vqmod_dir . substr($file, 0, -9); // quita .disabled

			if (file_exists($src)) {
				rename($src, $dst);
				$this->clearVqmodCache();
			}
		}

		$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
	}

	private function clearVqmodCache() {
		$vqmod_dir = dirname(DIR_APPLICATION) . '/vqmod/';

		@unlink($vqmod_dir . 'mods.cache');
		@unlink($vqmod_dir . 'checked.cache');

		$cache_files = glob($vqmod_dir . 'vqcache/*');
		if ($cache_files) {
			foreach ($cache_files as $f) {
				@unlink($f);
			}
		}
	}
}
?>
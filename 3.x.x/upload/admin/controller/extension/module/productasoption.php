<?php
//lib
require_once(DIR_SYSTEM.'library/tmd/system.php');
//lib
class ControllerExtensionModuleProductasoption extends Controller {
	private $error = array();
	public function install(){
		$this->load->model('extension/productasoption');
		$this->model_extension_productasoption->install();
	}	
	public function uninstall() {
		$this->load->model('extension/productasoption');
		$this->model_extension_productasoption->uninstall();
	}
	public function index() {
		$this->registry->set('tmd', new TMD($this->registry));
		$keydata=array(
		'code'=>'tmdkey_productasoption',
		'eid'=>'NDYzOTc=',
		'route'=>'extension/module/productasoption',
		);
		$productasoption=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->load->language('extension/module/productasoption');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_productasoption', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/productasoption', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/productasoption', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_productasoption_status'])) {
			$data['module_productasoption_status'] = $this->request->post['module_productasoption_status'];
		} else {
			$data['module_productasoption_status'] = $this->config->get('module_productasoption_status');
		}

		if (isset($this->request->post['module_productasoption_image_width'])) {
			$data['module_productasoption_image_width'] = $this->request->post['module_productasoption_image_width'];
		} else {
			$data['module_productasoption_image_width'] = $this->config->get('module_productasoption_image_width');
		}

		if (isset($this->request->post['module_productasoption_image_height'])) {
			$data['module_productasoption_image_height'] = $this->request->post['module_productasoption_image_height'];
		} else {
			$data['module_productasoption_image_height'] = $this->config->get('module_productasoption_image_height');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/productasoption', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/productasoption')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$productasoption=$this->config->get('tmdkey_productasoption');
		if (empty(trim($productasoption))) {			
		$this->session->data['warning'] ='Module will Work after add License key!';
		$this->response->redirect($this->url->link('extension/module/productasoption', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		return !$this->error;
	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_productasoption',
			'eid'=>'NDYzOTc=',
			'route'=>'extension/module/productasoption',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new TMD($this->registry));
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
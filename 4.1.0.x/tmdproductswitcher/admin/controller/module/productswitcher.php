<?php
namespace Opencart\Admin\Controller\Extension\tmdproductswitcher\Module;
// Lib Include 
require_once(DIR_EXTENSION.'/tmdproductswitcher/system/library/tmd/system.php');
// Lib Include 
/**
 * Class Account
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Module
 */
class Productswitcher extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
public function index() {
		$this->registry->set('tmd', new  \Tmdproductswitcher\System\Library\Tmd\System($this->registry));
		$keydata=array(
		'code'=>'tmdkey_productswitcher',
		'eid'=>'NDYzOTc=',
		'route'=>'extension/tmdproductswitcher/module/productswitcher',
		);
		$productswitcher=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->load->language('extension/tmdproductswitcher/module/productswitcher');

		$this->document->setTitle($this->language->get('heading_title1'));
		
		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdproductswitcher/module/productswitcher', 'user_token=' . $this->session->data['user_token'])
		];

		if(VERSION >= '4.0.2.0'){
			$data['action'] = $this->url->link('extension/tmdproductswitcher/module/productswitcher.save', 'user_token=' . $this->session->data['user_token']);
		}else{			
			$data['action'] = $this->url->link('extension/tmdproductswitcher/module/productswitcher|save', 'user_token=' . $this->session->data['user_token']);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data['module_productswitcher_status']      = $this->config->get('module_productswitcher_status');
	
		$data['module_productswitcher_image_width'] = $this->config->get('module_productswitcher_image_width');
	
		$data['module_productswitcher_image_height'] = $this->config->get('module_productswitcher_image_height');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdproductswitcher/module/productswitcher', $data));
	}


	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/tmdproductswitcher/module/productswitcher');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdproductswitcher/module/productswitcher')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		$productswitcher=$this->config->get('tmdkey_productswitcher');
		if (empty(trim($productswitcher))) {			
		$json['error'] ='Module will Work after add License key!';
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('module_productswitcher', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	
		public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_productswitcher',
			'eid'=>'NDYzOTc=',
			'route'=>'extension/tmdproductswitcher/module/productswitcher',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new  \Tmdproductswitcher\System\Library\Tmd\System($this->registry));
		
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(){
		$this->load->model('extension/tmdproductswitcher/tmd/productswitcher');
        $this->load->model('setting/event');
        $this->load->model('user/user_group');
        $this->model_extension_tmdproductswitcher_tmd_productswitcher->install();

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdproductswitcher/tmd/productswitcher');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdproductswitcher/tmd/productswitcher');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdproductswitcher/tmd/unswitchproduct_list');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdproductswitcher/tmd/unswitchproduct_list');
        
        //menu event
       if(VERSION>='4.0.2.0'){
            $eventaction='extension/tmdproductswitcher/module/productswitcher.menu';
        }else{
            $eventaction='extension/tmdproductswitcher/module/productswitcher|menu';
        }
        $this->model_setting_event->deleteEventByCode('tmd_product_switcher');
        $eventrequest=[
                    'code'=>'tmd_product_switcher',
                    'description'=>'TMD Product AS Option',
                    'trigger'=>'admin/view/common/column_left/before',
                    'action'=>$eventaction,
                    'status'=>'1',
                    'sort_order'=>'1',
                ];
                
        if(VERSION=='4.0.0.0'){
            $this->model_setting_event->addEvent('tmd_product_switcher', 'TMD Product AS Option', 'admin/view/common/column_left/before','extension/tmdproductswitcher/module/productswitcher|menu', true, 1);
        }else{
            $this->model_setting_event->addEvent($eventrequest);
        }

        //menu event
        if(VERSION>='4.0.2.0'){
            $eventaction='extension/tmdproductswitcher/tmd/optionevents.switchOptions';
        }else{
            $eventaction='extension/tmdproductswitcher/tmd/optionevents|switchOptions';
        }
        $this->model_setting_event->deleteEventByCode('tmd_show_switcher_option');
        $eventrequest=[
                    'code'=>'tmd_show_switcher_option',
                    'description'=>'TMD Product AS Option',
                    'trigger'=>'catalog/view/product/product/before',
                    'action'=>$eventaction,
                    'status'=>'1',
                    'sort_order'=>'1',
                ];
                
        if(VERSION=='4.0.0.0'){
            $this->model_setting_event->addEvent('tmd_show_switcher_option', 'TMD Product AS Option', 'catalog/view/product/product/before','extension/tmdproductswitcher/tmd/optionevents|switchOptions', true, 1);
        }else{
            $this->model_setting_event->addEvent($eventrequest);
        }
    }   
    
    public function uninstall() {
        $this->load->model('extension/tmdproductswitcher/tmd/productswitcher');
        $this->model_extension_tmdproductswitcher_tmd_productswitcher->uninstall();

		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdproductswitcher/tmd/productswitcher');
    	$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdproductswitcher/tmd/productswitcher');

    	$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdproductswitcher/tmd/unswitchproduct_list');
    	$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdproductswitcher/tmd/unswitchproduct_list');

		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('tmd_product_switcher');
		$this->model_setting_event->deleteEventByCode('tmd_show_switcher_option');
	}

	public function menu(string&$route, array&$args, mixed&$output):void {
        $modulestatus=$this->config->get('module_productswitcher_status');
        if(!empty($modulestatus)){
            $this->load->language('extension/tmdproductswitcher/module/productswitcher');
            
            $proswitch = [];
        
            if ($this->user->hasPermission('access', 'extension/tmdproductswitcher/module/productswitcher')) {        
                $proswitch[] = [
                    'name'       => $this->language->get('text_setting'),
                    'href'     => $this->url->link('extension/tmdproductswitcher/module/productswitcher', 'user_token=' . $this->session->data['user_token']),
                    'children' => []    
                ];                    
            }    
                
            if ($this->user->hasPermission('access', 'extension/tmdproductswitcher/tmd/productswitcher')) {        
                $proswitch[] = [
                    'name'       => $this->language->get('text_switch'),
                    'href'     => $this->url->link('extension/tmdproductswitcher/tmd/productswitcher', 'user_token=' . $this->session->data['user_token']),
                    'children' => []        
                ];                    
            }

            if ($this->user->hasPermission('access', 'extension/tmdproductswitcher/tmd/unswitchproduct_list')) {        
                $proswitch[] = [
                    'name'       => $this->language->get('text_unswitch'),
                    'href'     => $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list', 'user_token=' . $this->session->data['user_token']),
                    'children' => []        
                ];                    
            }    
                
            if ($proswitch) {                    
                $args['menus'][] = [
                    'id'       => 'menu-proswitch',
                    'icon'     => 'fa fa-toggle-on fw', 
                    'name'     => $this->language->get('text_switch'),
                    'href'     => '',
                    'children' => $proswitch
                ];        
            }
        }
    }


}
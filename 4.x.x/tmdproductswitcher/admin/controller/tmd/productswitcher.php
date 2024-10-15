<?php
namespace Opencart\Admin\Controller\Extension\Tmdproductswitcher\Tmd;
/**
 * Class Account
 *
 * @package Opencart\Admin\Controller\Extension\Tmdproductswitcher\Tmd
 */
use \Opencart\System\Helper as Helper;
class Productswitcher extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */

	public function index(): void {
		$this->load->language('extension/tmdproductswitcher/tmd/productswitcher');
		$this->load->model('extension/tmdproductswitcher/tmd/productswitcher');

		$this->document->setTitle($this->language->get('heading_title1'));

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
		 	$filter_product = '';
		}

		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
		 	$filter_product_id = '';
		}

		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . (string)$this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . (string)$this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . (int)$this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdproductswitcher/tmd/productswitcher', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['VERSION'] = VERSION;

		if(VERSION >='4.0.2.0'){
			$data['add'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.delete', 'user_token=' . $this->session->data['user_token']);
		}else{
			$data['add'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher|form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher|delete', 'user_token=' . $this->session->data['user_token']);
		}

	

		$data['list'] = $this->getList();
		
		$data['filter_product']    = $filter_product;
		$data['filter_product_id'] = $filter_product_id;

		$data['reset']    = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher&user_token='.$this->session->data['user_token']);

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdproductswitcher/tmd/productswitcher', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/tmdproductswitcher/tmd/productswitcher');
		$this->load->model('extension/tmdproductswitcher/tmd/productswitcher');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/tmdproductswitcher/tmd/productswitcher');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdproductswitcher/tmd/productswitcher')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		

		// new work
		if (empty($this->request->post['main_product_id'])) {
			$json['error']['product_name'] = $this->language->get('error_mainproduct');
		}
		// new work

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('extension/tmdproductswitcher/tmd/productswitcher');

			if (!$this->request->post['switch_id']) {
				$json['switch_id'] = $this->model_extension_tmdproductswitcher_tmd_productswitcher->addproductswitcher($this->request->post);
			} else {
				$this->model_extension_tmdproductswitcher_tmd_productswitcher->editproductswitcher($this->request->post['switch_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('extension/tmdproductswitcher/tmd/productswitcher');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'extension/tmdproductswitcher/tmd/productswitcher')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('extension/tmdproductswitcher/tmd/productswitcher');

			foreach ($selected as $switch_id) {
				$this->model_extension_tmdproductswitcher_tmd_productswitcher->deleteproductswitcher($switch_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function getList() {
		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
		 	$filter_product = '';
		}

		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
		 	$filter_product_id = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdproductswitcher/tmd/productswitcher', 'user_token=' . $this->session->data['user_token'] . $url, true)
		];

		if(VERSION >='4.0.2.0'){
			$data['add']      = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.add', 'user_token=' . $this->session->data['user_token'] . $url, true);
			$data['delete']   = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
			$data['action'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.list', 'user_token=' . $this->session->data['user_token'] . $url);

		}else{
			$data['add']      = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher|add', 'user_token=' . $this->session->data['user_token'] . $url, true);
			$data['delete']   = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher|delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
			$data['action'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher|list', 'user_token=' . $this->session->data['user_token'] . $url);
		}

		

		$data['variations'] = [];

		$filter_data = [
			'filter_product' 	=> $filter_product,
			'filter_product_id' => $filter_product_id,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'             => $this->config->get('config_pagination_admin')
		];
		
		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		
		$variation_total = $this->model_extension_tmdproductswitcher_tmd_productswitcher->getTotalproductswitchers($filter_data);

		$results = $this->model_extension_tmdproductswitcher_tmd_productswitcher->getproductswitchers($filter_data);

		foreach ($results as $result) {
			
			$switchoptions = [];
			
			$proinfo = $this->model_catalog_product->getProduct($result['main_product_id']);
			if(!empty($proinfo['name'])){
				$proname = $proinfo['name'];
			} else {
				$proname = '';
			}
			
			$options = $this->model_extension_tmdproductswitcher_tmd_productswitcher->getproductswitcherOptions($result['switch_id']);
			foreach ($options as $option) {
				$proinfo = $this->model_catalog_product->getProduct($option['product_id']);
	
				if(!empty($proinfo['name'])){
					$prooptname = $proinfo['name'];
				} else {
					$prooptname = '';
				}

				if(!empty($proinfo['image'])){
					$image = $proinfo['image'];
				} else {
					$image = '';
				}
				
				if (is_file(DIR_IMAGE . $image)) {
					$image = $this->model_tool_image->resize($image, 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}
				
				$switchoptions[] = [
				
					'prooptname'   => $prooptname,
					'image'        => $image,
				];
			 }

			if($result['status'] == '1'){
         $status = $this->language->get('text_enabled');
			}else{
         $status = $this->language->get('text_disabled');
			} 

			if(VERSION >='4.0.2.0'){
				$edit = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.form', 'user_token=' . $this->session->data['user_token'] . '&switch_id=' . $result['switch_id'] . $url);
			}else{
				$edit = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher|form', 'user_token=' . $this->session->data['user_token'] . '&switch_id=' . $result['switch_id'] . $url);
			}
			
			$data['switchers'][] =[
				'switch_id'       => $result['switch_id'],
				'status'          => $status,
				'proname'         => $proname,
				'switchoptions'   => $switchoptions,
				'edit'            => $edit
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
		
		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if(VERSION >='4.0.2.0'){
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $variation_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);
		}else{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $variation_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdproductswitcher/tmd/productswitcher|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);
		}

		$data['results'] = sprintf($this->language->get('text_pagination'), ($variation_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($variation_total - $this->config->get('config_pagination_admin'))) ? $variation_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $variation_total, ceil($variation_total / $this->config->get('config_pagination_admin')));

		$data['sort']           = $sort;
		$data['order']          = $order;
		$data['filter_product'] = $filter_product;
		$data['filter_product_id'] = $filter_product_id;

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		return $this->load->view('extension/tmdproductswitcher/tmd/productswitcher_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('extension/tmdproductswitcher/tmd/productswitcher');
		$this->load->model('extension/tmdproductswitcher/tmd/productswitcher');

		$this->document->setTitle($this->language->get('heading_title1'));
		$data['text_form'] = !isset($this->request->get['switch_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdproductswitcher/tmd/productswitcher', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if(VERSION >='4.0.2.0'){
			$data['save'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher.save', 'user_token=' . $this->session->data['user_token']);
		}else{
			$data['save'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher|save', 'user_token=' . $this->session->data['user_token']);		
		}

		$data['back'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher', 'user_token=' . $this->session->data['user_token'] . $url);


        $this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['VERSION'] = VERSION;

		$data['cancel'] = $this->url->link('extension/tmdproductswitcher/tmd/productswitcher', 'user_token=' . $this->session->data['user_token'] . $url, true);
			
		if (isset($this->request->get['switch_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$proswitch_info = $this->model_extension_tmdproductswitcher_tmd_productswitcher->getproductswitcher($this->request->get['switch_id']);
		}

    if(!empty($proswitch_info['heading_label'])){
          $headinglabelinfos = unserialize($proswitch_info['heading_label']);
		}else{
		  $headinglabelinfos = [];	
		}
		
		    $data['productswitcher'] = [];
            foreach($headinglabelinfos as $language_id => $headinglabelinfo){
                 $data['productswitcher'][$language_id] = [
                 	'heading_label'=>$headinglabelinfo['heading_label']
                 ];
					 
            }
         

		$data['user_token'] = $this->session->data['user_token'];

		if (!empty($this->request->get['switch_id'])) {
			$data['switch_id'] = $this->request->get['switch_id'];
		} else {
			$data['switch_id'] = '';
		}

		if (!empty($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($proswitch_info['status'])) {
			$data['status'] = $proswitch_info['status'];
		} else {
			$data['status'] = '';
		}
		
		if (!empty($this->request->post['main_product_id'])) {
			$data['main_product_id'] = $this->request->post['main_product_id'];
		} elseif (!empty($proswitch_info['main_product_id'])) {
			$data['main_product_id'] = $proswitch_info['main_product_id'];
		} else {
			$data['main_product_id'] = 0;
		}
		
		$this->load->model('catalog/product');
		$proinfo = $this->model_catalog_product->getProduct($data['main_product_id']);
		if (!empty($this->request->post['product_name'])) {
			$data['product_name'] = $this->request->post['product_name'];
		} elseif (!empty($proinfo['name'])) {
			$data['product_name'] = $proinfo['name'];
		} else {
			$data['product_name'] = '';
		}
		
		
		$data['productswitcheroptions'] = [];
		$options = $this->model_extension_tmdproductswitcher_tmd_productswitcher->getproductswitcherOptions($data['switch_id']);
		foreach ($options as $option) {
			
			$proinfo = $this->model_catalog_product->getProduct($option['product_id']);
			if(!empty($proinfo['name'])){
				$prooptname = $proinfo['name'];
			} else {
				$prooptname = '';
			}

			if(!empty($option['sort_order'])){
				$sort_order = $option['sort_order'];
			} else {
				$sort_order = '';
			}

			
            $label=[];
            $labelinfos = unserialize($option['label']);
            foreach($labelinfos as $key=> $labelinfo){
               $label[$key]= [
               	'label'=>$labelinfo['label']
               ];

            }

     if(!empty($proinfo['image'])){
				$image = $proinfo['image'];
			} else {
				$image = '';
			}

         
             
			$this->load->model('tool/image');
			if (is_file(DIR_IMAGE . $image)) {
				$image = $this->model_tool_image->resize($image,  40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png',  40, 40);
			}
			
			
				
				$data['productswitcheroptions'][] =[
				
					'product_id'  => $option['product_id'],
					
					'sort_order'  => $sort_order,
					'switch_label' => $label,
					'prooptname'  => $prooptname,
					'image'       => $image
				];
			
		
		}
		
		
		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdproductswitcher/tmd/productswitcher_form', $data));
	}
	
	public function autocomplete() {
		$json = [];

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('extension/tmdproductswitcher/tmd/productswitcher');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = [
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			];

			$results = $this->model_extension_tmdproductswitcher_tmd_productswitcher->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = [];
				
				$this->load->model('tool/image');

        if(!empty($result['image'])){
           $resultimage = $result['image'];
        }else{
          $resultimage  ='';

        }

				if (is_file(DIR_IMAGE . $resultimage)) {
					$image = $this->model_tool_image->resize($resultimage, 60, 60);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 60, 60);
				}
				
				$json[] = [
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'] , ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'image'      => $image,
				];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	///autocomplete customers

	///autocomplete products
		public function autocompleteProducts() { 
		if(isset($this->session->data['token'])){// free product
		    $tokenexchange = 'token=' . $this->session->data['token'];
		} else{
		    $tokenexchange='user_token=' . $this->session->data['user_token'];
		}

		$json = [];

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = (int)$this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = [
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			];

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = [];

				$product_options = $this->model_catalog_product->getOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = [];

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = [
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								];
							}
						}

						$option_data[] = [
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						];
					}
				}
                
        $url='';
        $this->load->model('tool/image'); 
       
        if(!empty($result['image'])){
           $resultimage = $result['image'];
        }else{
          $resultimage  ='';

        }

				if ($resultimage) { // free product append image
					$prodctimage = str_replace(' ','%20',$this->model_tool_image->resize($resultimage, 100, 100));
				} else {
					$prodctimage = $this->model_tool_image->resize('no_image.png', 100, 100);
				}

				$json[] = [
					'prodctimage' => $prodctimage,// free product append image
					'edit'       => $this->url->link('catalog/product/edit', $tokenexchange . '&product_id=' . $result['product_id'] . $url, true),
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	///autocomplete products
	public function getMatchproduct() {
		$json = [];
		if (isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');
			$result = $this->model_catalog_product->getProduct($this->request->get['product_id']);
			$this->load->model('tool/image');

       if(!empty($result['image'])){
           $resultimage = $result['image'];
        }else{
          $resultimage  ='';

        }

			if (is_file(DIR_IMAGE .  $resultimage)) {
				$image = $this->model_tool_image->resize($resultimage, 60, 60);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 60, 60);
			}
			
			$json= [
				'product_id' => $result['product_id'],
			    'name'       => $result['name'],
				  'image'      => $image,
			];
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
<?php
class ControllerExtensionProductasoption extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/productasoption');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/productasoption');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/productasoption');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/productasoption');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_productasoption->addproductasoption($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/productasoption', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/productasoption');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/productasoption');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_productasoption->editproductasoption($this->request->get['switch_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/productasoption', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/productasoption');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/productasoption');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $switch_id) {
				$this->model_extension_productasoption->deleteproductasoption($switch_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/productasoption', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
		 	$filter_product = '';
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/productasoption', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add']      = $this->url->link('extension/productasoption/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete']   = $this->url->link('extension/productasoption/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete']   = $this->url->link('extension/productasoption/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['reset']    = $this->url->link('extension/productasoption&user_token='.$this->session->data['user_token']);

		$data['variations'] = array();

		$filter_data = array(
			'filter_product' 	=> $filter_product,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);
		
		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		
		$variation_total = $this->model_extension_productasoption->getTotalproductasoptions($filter_data);

		$results = $this->model_extension_productasoption->getproductasoptions($filter_data);

		foreach ($results as $result) {
			
			$switchoptions = array();
			
			$proinfo = $this->model_catalog_product->getProduct($result['main_product_id']);
			if(isset($proinfo['name'])){
				$proname = $proinfo['name'];
			} else {
				$proname = '';
			}
			
			 $options = $this->model_extension_productasoption->getproductasoptionOptions($result['switch_id']);
			 foreach ($options as $option) {
				$proinfo = $this->model_catalog_product->getProduct($option['product_id']);
	
				if(isset($proinfo['name'])){
					$prooptname = $proinfo['name'];
				} else {
					$prooptname = '';
				}	

				if(!empty($proinfo['image'])){
					$prooptimg = $proinfo['image'];
				} else {
					$prooptimg = '';
				}
				


				if (is_file(DIR_IMAGE . $prooptimg)) {
					$image = $this->model_tool_image->resize($prooptimg, 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}
				
				$switchoptions[] = array(
					//'optionname'   => $option['name'],
					'prooptname'   => $prooptname,
					'image'   => $image,
				);
			 }

			if($result['status'] == '1'){
              $status = $this->language->get('text_enabled');
			}else{
              $status = $this->language->get('text_disabled');
			} 
			
			$data['switchers'][] = array(
				'switch_id'       => $result['switch_id'],
				'status'          => $status,
				'proname'         => $proname,
				'switchoptions'   => $switchoptions,
				'edit'            => $this->url->link('extension/productasoption/edit', 'user_token=' . $this->session->data['user_token'] . '&switch_id=' . $result['switch_id'] . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
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

		$data['sort_name'] = $this->url->link('extension/productasoption', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
		
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

		$pagination        = new Pagination();
		$pagination->total = $variation_total;
		$pagination->page  = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url   = $this->url->link('extension/productasoption', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($variation_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($variation_total - $this->config->get('config_limit_admin'))) ? $variation_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $variation_total, ceil($variation_total / $this->config->get('config_limit_admin')));

		$data['sort']           = $sort;
		$data['order']          = $order;
		$data['filter_product'] = $filter_product;
		$this->load->model('catalog/product');
		if(isset($data['filter_product'])) {
			$product_info = $this->model_catalog_product->getProduct($data['filter_product']);
		}
		if(isset($product_info['name'])) {
			$data['productname'] = $product_info['name'];
		} else {
			$data['productname'] ='';
		}

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/productasoption_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['switch_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['product_name'])) {
			$data['error_product'] = $this->error['product_name'];
		} else {
			$data['error_product'] = '';
		}

		if (isset($this->error['main_product_id'])) {
			$data['error_mainproduct'] = $this->error['main_product_id'];
		} else {
			$data['error_mainproduct'] = '';
		}
		
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/productasoption', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);


        $this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();


		if (!isset($this->request->get['switch_id'])) {
			$data['action'] = $this->url->link('extension/productasoption/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/productasoption/edit', 'user_token=' . $this->session->data['user_token'] . '&switch_id=' . $this->request->get['switch_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/productasoption', 'user_token=' . $this->session->data['user_token'] . $url, true);
			
		if (isset($this->request->get['switch_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$proswitch_info = $this->model_extension_productasoption->getproductasoption($this->request->get['switch_id']);
		}

        if(!empty($proswitch_info['heading_label'])){
          $headinglabelinfos = unserialize($proswitch_info['heading_label']);
		}else{
		  $headinglabelinfos = [];	
		}
		
		    $data['productasoption'] = [];
            foreach($headinglabelinfos as $language_id => $headinglabelinfo){
                 $data['productasoption'][$language_id] =array('heading_label'=>$headinglabelinfo['heading_label']);
					 
            }
         

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['switch_id'])) {
			$data['switch_id'] = $this->request->get['switch_id'];
		} else {
			$data['switch_id'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($proswitch_info['status'])) {
			$data['status'] = $proswitch_info['status'];
		} else {
			$data['status'] = '';
		}
		
		if (isset($this->request->post['main_product_id'])) {
			$data['main_product_id'] = $this->request->post['main_product_id'];
		} elseif (!empty($proswitch_info['main_product_id'])) {
			$data['main_product_id'] = $proswitch_info['main_product_id'];
		} else {
			$data['main_product_id'] = '';
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
		
		
		$data['productasoptionoptions'] = array();
		$options = $this->model_extension_productasoption->getproductasoptionOptions($data['switch_id']);
		foreach ($options as $option) {
			
			$proinfo = $this->model_catalog_product->getProduct($option['product_id']);
			if(isset($proinfo['name'])){
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
               $label[$key]= array('label'=>$labelinfo['label']);

            }

         
             
			$this->load->model('tool/image');

			if(!empty($proinfo['image'])){
             $proinfoimage = $proinfo['image'];
			}else{
             $proinfoimage = '';
			}
			
			if (is_file(DIR_IMAGE . $proinfoimage)) {
				$image = $this->model_tool_image->resize($proinfoimage,  40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png',  40, 40);
			}
			
			
				
				$data['productasoptionoptions'][] = array(
				
					'product_id'  => $option['product_id'],
					
					'sort_order'  => $sort_order,
					'switch_label' => $label,
					'prooptname'  => $prooptname,
					'image'       => $image
				);
			
		
		}
		
		
		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/productasoption_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/productasoption')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['product_name']) < 1) || (utf8_strlen($this->request->post['product_name']) > 64)) {
			$this->error['product_name'] = $this->language->get('error_product');
		}

		// new work
		if (empty($this->request->post['main_product_id'])) {
			$this->error['product_name'] = $this->language->get('error_mainproduct');
		}
		// new work
		
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/productasoption')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function autocomplete() {
		$json = array();

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
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$this->load->model('extension/productasoption');

			$results = $this->model_extension_productasoption->getProducts($filter_data);

			foreach ($results as $result) {
				
				$this->load->model('tool/image');

				if(!empty($result['image'])){
                   $resultimage = $result['image'];
                }else{
                   $resultimage = '';
                }
                
				if (is_file(DIR_IMAGE .  $resultimage)) {
					$image = $this->model_tool_image->resize( $resultimage, 60, 60);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 60, 60);
				}
				
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'] , ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'image'      => $image,
				);
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

		$json = array();

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

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}
                
                 $url='';
                $this->load->model('tool/image'); 

                if(!empty($result['image'])){
                   $resultimage = $result['image'];
                }else{
                   $resultimage = '';
                }


				if ($resultimage) { // free product append image
					$prodctimage = str_replace(' ','%20',$this->model_tool_image->resize($resultimage, 100, 100));
				} else {
					$prodctimage = $this->model_tool_image->resize('no_image.png', 100, 100);
				}

				$json[] = array(
					'prodctimage' => $prodctimage,// free product append image
					'edit'       => $this->url->link('catalog/product/edit', $tokenexchange . '&product_id=' . $result['product_id'] . $url, true),
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	///autocomplete products
	public function getMatchproduct() {
		$json = array();
		if (isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');
			$result = $this->model_catalog_product->getProduct($this->request->get['product_id']);
			$this->load->model('tool/image');

			if(!empty($result['image'])){
               $resultimage = $result['image'];
            }else{
               $resultimage = '';
            }


			if (is_file(DIR_IMAGE . $resultimage)) {
				$image = $this->model_tool_image->resize($resultimage, 60, 60);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 60, 60);
			}
			$json= array(
				'product_id' => $result['product_id'],
			    'name'       => $result['name'],
				'image'      => $image,
			);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
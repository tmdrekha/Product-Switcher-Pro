<?php
namespace Opencart\Admin\Controller\Extension\Tmdproductswitcher\Tmd;
/**
 * Class Account
 *
 * @package Opencart\Admin\Controller\Extension\tmdproductswitcher\Tmd
 */
use \Opencart\System\Helper as Helper;
class UnswitchProductList extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */


	public function index(): void {
		$this->load->language('extension/tmdproductswitcher/tmd/unswitchproduct_list');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->document->setTitle($this->language->get('heading_title1'));

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

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = '';
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}


		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'href' => $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['VERSION'] = VERSION;

		$data['list'] = $this->getList();
		
		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_price'] = $filter_price;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_status'] = $filter_status;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdproductswitcher/tmd/unswitchproduct', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/tmdproductswitcher/tmd/unswitchproduct_list');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return void
	 */

	protected function getList() {
		$this->load->model('extension/tmdproductswitcher/tmd/unswitchproduct_list');
		$this->load->model('catalog/product');
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

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = '';
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}


		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true)
		];

		$data['action'] =  $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['add'] = $this->url->link('catalog/product/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['copy'] = $this->url->link('catalog/product/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/product/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['products'] = [];

		$filter_data = [
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'           => $this->config->get('config_pagination_admin')
		];

		if(VERSION >='4.0.2.0'){
			$data['action'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list.list', 'user_token=' . $this->session->data['user_token'] . $url);

		}else{
			$data['action'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list|list', 'user_token=' . $this->session->data['user_token'] . $url);
		}

		$data['VERSION'] = VERSION;
		$this->load->model('tool/image');

		$product_total = $this->model_extension_tmdproductswitcher_tmd_unswitchproduct_list->getTotalProductSwitchers($filter_data);

		$results = $this->model_extension_tmdproductswitcher_tmd_unswitchproduct_list->getunSwitchProducts($filter_data);
		foreach ($results as $result) {

			  if(!empty($result['image'])){
                $resultimage  = $result['image'];
              }else{
                $resultimage  ='';
              }
	
			if (is_file(DIR_IMAGE . $resultimage)) {
				$image = $this->model_tool_image->resize($resultimage, 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

		

	

			$data['products'][] = [
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'quantity'   => $result['quantity'],
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'       => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'] . $url, true)
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if(VERSION >='4.0.2.0'){
			$data['sort_name'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list.list', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
			$data['sort_model'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list.list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.model' . $url, true);
			$data['sort_price'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list.list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.price' . $url, true);
			$data['sort_quantity'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list.list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.quantity' . $url, true);
			$data['sort_status'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list.list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.status' . $url, true);
			$data['sort_order'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list.list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);
		}else{
			$data['sort_name'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list|list', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
			$data['sort_model'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list|list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.model' . $url, true);
			$data['sort_price'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list|list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.price' . $url, true);
			$data['sort_quantity'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list|list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.quantity' . $url, true);
			$data['sort_status'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list|list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.status' . $url, true);
			$data['sort_order'] = $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list|list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if(VERSION >='4.0.2.0'){
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $product_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);
		}else{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $product_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdproductswitcher/tmd/unswitchproduct_list|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);
		}
		

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($product_total - $this->config->get('config_pagination_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $product_total, ceil($product_total / $this->config->get('config_pagination_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_price'] = $filter_price;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;
		return $this->load->view('extension/tmdproductswitcher/tmd/unswitchproduct_list', $data);
	}

}
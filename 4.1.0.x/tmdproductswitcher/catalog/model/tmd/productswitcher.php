<?php
namespace Opencart\Catalog\Model\Extension\Tmdproductswitcher\Tmd;
/**
 * Class Account
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Module
 */
class Productswitcher extends \Opencart\System\Engine\Model {
	/**
	 * @return void
	 */


	public function getProductSwitchOptions($product_id) {
		$product_option_data = [];
		$main_product_id=0;
		$query=$this->db->query("SELECT * FROM `" . DB_PREFIX . "productswitcher_product` WHERE product_id = '" . (int)$product_id . "'");
		
		if(!empty($query->row['main_product_id'])){
			$main_product_id=$query->row['main_product_id'];
		}

		if(empty($main_product_id)){
			$query=$this->db->query("SELECT main_product_id FROM `" . DB_PREFIX . "productswitcher` WHERE main_product_id = '" . (int)$product_id . "' AND status='1'");
			if(!empty($query->row['main_product_id']))
			{
				$main_product_id=$query->row['main_product_id'];
			}
		
		}
		
		
		if(!empty($main_product_id)){
			$product_option_data = [];
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "productswitcher` WHERE main_product_id = '" . (int)$main_product_id . "' AND status='1'");
			
			if(isset($query->row['heading_label'])){
			$mainheading=unserialize($query->row['heading_label']);
		     }
			
			$mainheadingtext='';
			if(!empty($mainheading[$this->config->get('config_language_id')])){
			$mainheadingtext=$mainheading[$this->config->get('config_language_id')]['heading_label'];
			}
			
			$product_option_value_data=[];

			if(!empty($query->row['switch_id'])){
              $switch_id = $query->row['switch_id'];
			}else{
			  $switch_id = 0;
			}
			
			$queryoptions=$this->db->query("SELECT * FROM `" . DB_PREFIX . "productswitcher_product` WHERE switch_id = '" . (int)$switch_id . "' order by sort_order");
			foreach($queryoptions->rows as $queryoption){
						$variation_status=true;
						$link = $this->url->link('product/product&product_id='.$queryoption['product_id']);
						$productinfromation = $this->db->query("SELECT image FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$queryoption['product_id']. "'")->row;
						
						
						$optionvalue = unserialize($queryoption['label']);
						
						$optionvaluename='';
						if(!empty($optionvalue[$this->config->get('config_language_id')]['label'])){
						  $optionvaluename=$optionvalue[$this->config->get('config_language_id')]['label'];
						}
						
						
						$product_option_value_data[] = [
							'product_option_value_id' => '',
							'option_value_id'         =>'',
							'name'                    => $optionvaluename,
							'image'                   => !empty($productinfromation['image']) ? $productinfromation['image'] : '',
							'quantity'                => '',
							'subtract'                => '',
							'price'                   => '',
							'price_prefix'            => '',
				            'points'        		  => '',
				            'points_prefix' 		  => '',
							'weight'                  => '',
							'weight_prefix'           => '',
							'variation_status'        => $variation_status,
							'link'           		  => $link,
							'product_id'           		  => $queryoption['product_id'],
						];
				
			}
		
			if(!empty($product_option_value_data)){
			$product_option_data[] = [
					'product_option_id'    => '',
					'product_option_value' => $product_option_value_data,
					'option_id'            => 0,
					'name'                 => $mainheadingtext,
					'type'                 => 'radio',
					'value'                =>'',
					'required'             => '',
				];
				return $product_option_data;
			}
			
		}
	}

}

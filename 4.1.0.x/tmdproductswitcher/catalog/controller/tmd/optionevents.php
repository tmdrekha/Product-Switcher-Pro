<?php
namespace Opencart\Catalog\Controller\Extension\Tmdproductswitcher\Tmd;
/**
 * Class Account
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Module
 */
class Optionevents extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */

	public function switchOptions(string&$route, array&$args, mixed&$output){
		$modulestatus=$this->config->get('module_productswitcher_status');
        if(!empty($modulestatus)){
        	$mainoption = $args['options'];
			$args['options'] = [];
			$this->load->model('extension/tmdproductswitcher/tmd/productswitcher');
			if(!empty($this->model_extension_tmdproductswitcher_tmd_productswitcher->getProductSwitchOptions($this->request->get['product_id']))){
				foreach ($this->model_extension_tmdproductswitcher_tmd_productswitcher->getProductSwitchOptions($this->request->get['product_id']) as $option) {
					$product_option_value_data = [];

					foreach ($option['product_option_value'] as $option_value) {
						
							if($option_value['product_id']==$this->request->get['product_id']) {
								$variation_selected = true;
							} else {
								$variation_selected = false;
							}

							   if(!empty($this->config->get('module_productswitcher_image_height'))){
	                            $image_hight = $this->config->get('module_productswitcher_image_height');
	                           }else{
	                           $image_hight = '50';
	                            }

					            if(!empty($this->config->get('module_productswitcher_image_width'))){
					               $image_width = $this->config->get('module_productswitcher_image_width');
					            }else{
					               $image_width = '50';
					            }

                                $this->load->model('tool/image');
					            if(!empty($option_value['image'])){
					               $image = $option_value['image'];
					            }else{
					               $image = 'no_image.png';
					            }

							$product_option_value_data[] = [
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize( $image, $image_width,  $image_hight),
								'price'                   => '',
								'price_prefix'            => '',
								'variation_status'        => $option_value['variation_status'],
								'link'           		  => $option_value['link'],
								'variation_selected'      => $variation_selected,
							];
						
					}

					$args['options'][] = [
						'product_option_id'    => $option['product_option_id'],
						'product_option_value' => $product_option_value_data,
						'option_id'            => $option['option_id'],
						'name'                 => $option['name'],
						'type'                 => 'tmdswitcher',
						'value'                => $option['value'],
						'required'             => $option['required']
					];
				}
			}


			if(!empty($args['options'])){
				$args['options']=array_merge($args['options'],$mainoption);
			}else{
				$args['options']= $mainoption;
			}
			
			if ($route == 'product/product' && isset($this->request->get['product_id'])) {
				$args['prohref'] = $this->url->link('product/product', '&product_id=' . $this->request->get['product_id']);
			}

	        $template_buffer = $this->getTemplateBuffer($route,$output);			
			$find    = "{% if option.type == 'radio' %}";
			$replace = file_get_contents(DIR_EXTENSION.'tmdproductswitcher/catalog/view/template/tmd/productswitcher.twig')."{% if option.type == 'radio' %}";
			$output  = str_replace( $find, $replace, $template_buffer );
	    }
	}

	protected function getTemplateBuffer($route, $event_template_buffer) {

        // if there already is a modified template from view/*/before events use that one
        if ($event_template_buffer) {
            return $event_template_buffer;
        }

        // load the template file (possibly modified by ocmod and vqmod) into a string buffer

        if ($this->config->get('config_theme') == 'default') {
            $theme = $this->config->get('theme_default_directory');
        } else {
            $theme = $this->config->get('config_theme');
        }
        $dir_template = DIR_TEMPLATE;

        $template_file = $dir_template.$route.'.twig';
        if (file_exists($template_file) && is_file($template_file)) {

            return file_get_contents($template_file);
        }
        
        $dir_template  = DIR_TEMPLATE.'default/template/';
        $template_file = $dir_template.$route.'.twig';
        if (file_exists($template_file) && is_file($template_file)) {

            return file_get_contents($template_file);
        }
        trigger_error("Cannot find template file for route '$route'");
        exit;
    }

}

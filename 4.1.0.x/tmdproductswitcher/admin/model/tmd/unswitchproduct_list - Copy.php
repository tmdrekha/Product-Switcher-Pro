<?php
namespace Opencart\Admin\Model\Extension\Tmdproductswitcher\Tmd;
/**
 * Class Productswitcher
 *
 * @package Opencart\Admin\Controller\Extension\tmdproductswitcher\Tmd
 */
class Unswitchproductlist extends \Opencart\System\Engine\Model {
	/**
	 * @return void
	 */

	public function getunSwitchProducts($productids,$data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.product_id NOT IN ('". $productids . "') AND switch_status =0";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		/*product report filter start!*/

		if (!empty($data['filter_upc'])) {
			$sql .= " AND p.upc = ''";
		}

		if (!empty($data['filter_image'])) {
		 	$sql .= " AND p.image = ''";
		}

		if (!empty($data['filter_less5'])) {
		 	$sql .= " AND p.quantity <= 5";
		}

	    if (!empty($data['filter_specialprice'])) {
		 	$sql .= " AND ps.product_id is NULL";
		}
			
	    /*product report filter end!*/

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}



	public function getTotalProductSwitchers($productids,$data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.product_id NOT IN ('". $productids . "') AND switch_status =0";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		/*product report filter start!*/

		if (!empty($data['filter_upc'])) {
			$sql .= " AND p.upc = ''";
		}

		if (!empty($data['filter_image'])) {
		 	$sql .= " AND p.image = ''";
		}

		if (!empty($data['filter_less5'])) {
		 	$sql .= " AND p.quantity <= 5";
		}

		if (!empty($data['filter_specialprice'])) {
		 	$sql .= " AND ps.product_id is NULL";
		}
		/*product report filter end!*/

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProductswitchids() {
		$product_switch_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "productswitcher_product");
		foreach ($query->rows as $result) {
			$product_switch_data[] = $result['product_id'];
		}
		return $product_switch_data;
	}

}

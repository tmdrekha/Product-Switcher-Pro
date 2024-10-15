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

    public function getproductss() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "productswitcher_product WHERE product_id<>0");
		return $query->rows;
	}

		
      public function getunSwitchProducts(array $data = []): array {

	    $implode = [];
		$prodata = $this->getproductss();
		foreach ($prodata as $key => $value) {
			$implode[] = $value['product_id'];
		}

		$sql = "SELECT * FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($implode)) {
			$sql .= " AND p.product_id NOT IN(" . implode(",", $implode).")";
		}

		if (!empty($data['filter_master_id'])) {
			$sql .= " AND p.`master_id` = '" . (int)$data['filter_master_id'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.`model` LIKE '" . $this->db->escape((string)$data['filter_model'] . '%') . "'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.`price` LIKE '" . $this->db->escape((string)$data['filter_price'] . '%') . "'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.`quantity` = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.`product_id`";

		$sort_data = [
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.`name`";
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



public function getTotalProductSwitchers(array $data = []): int {

	    $implode = [];
		$prodata = $this->getproductss();
		foreach ($prodata as $key => $value) {
			$implode[] = $value['product_id'];
		}

		$sql = "SELECT COUNT(DISTINCT p.`product_id`) AS `total` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";


		if (!empty($implode)) {
			$sql .= " AND p.product_id NOT IN(" . implode(",", $implode).")";
		}

		if (!empty($data['filter_master_id'])) {
			$sql .= " AND p.`master_id` = '" . (int)$data['filter_master_id'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.`model` LIKE '" . $this->db->escape((string)$data['filter_model'] . '%') . "'";
		}

		if (isset($data['filter_price']) && $data['filter_price'] !== '') {
			$sql .= " AND p.`price` LIKE '" . $this->db->escape((string)$data['filter_price'] . '%') . "'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.`quantity` = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}


}

<?php
namespace Opencart\Admin\Model\Extension\Tmdproductswitcher\Tmd;
/**
 * Class productswitcher
 *
 * @package Opencart\Admin\Controller\Extension\Tmdproductswitcher\Tmd
 */
class Productswitcher extends \Opencart\System\Engine\Model {
	/**
	 * @return void
	 */

	public function install() {
    // Create main switcher table
    $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."productswitcher` (
        `switch_id` int(11) NOT NULL AUTO_INCREMENT,
        `main_product_id` int(11) NOT NULL,
        `heading_label` text NOT NULL,
        `status` int(11) NOT NULL,
        PRIMARY KEY (`switch_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

    // Create switcher-product relationship table
    $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."productswitcher_product` (
        `switchproduct_id` int(11) NOT NULL AUTO_INCREMENT,
        `main_product_id` int(11) NOT NULL,
        `switch_id` int(11) NOT NULL,
        `product_id` int(11) NOT NULL,
        `sort_order` int(11) NOT NULL,
        `label` text NOT NULL,
        `image` text NOT NULL,
        PRIMARY KEY (`switchproduct_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

    // Check if 'switch_status' column exists in 'product' table before adding it
      $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'switch_status'");
    if (!$query->num_rows) {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `switch_status` INT(11) NOT NULL AFTER `status`");
    }
}


	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."productswitcher`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."productswitcher_product`");
        $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'switch_status'");
    if ($query->num_rows) {
      $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` DROP COLUMN `switch_status`");
   }
	}

	public function addproductswitcher($data) {
	    if(!empty($data['productswitcher'])) {
           $headinglabel = serialize($data['productswitcher']);
	    }

		$this->db->query("INSERT INTO " . DB_PREFIX . "productswitcher SET status = '" . (int)$data['status'] . "', main_product_id = '" . (int)$data['main_product_id'] . "', heading_label = '" . $this->db->escape($headinglabel) . "'");

		$switch_id = $this->db->getLastId();
		
		if(isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
	            if(!empty($product_option['switch_label'])) {
                   $label = serialize($product_option['switch_label']);
	             }

                if(!empty($product_option['product_id'])){
				$this->db->query("INSERT INTO " . DB_PREFIX . "productswitcher_product SET 
				switch_id       = '" . (int)$switch_id . "',
				main_product_id = '" . (int)$data['main_product_id'] . "', 
				product_id = '" . (int)$product_option['product_id'] . "', 
				sort_order = '" . (int)$product_option['sort_order'] . "',
				label      = '" . $this->db->escape($label) . "',
				image      = '" . $this->db->escape($product_option['image']) . "'"); 
			    }
			    }
		} 

		// switch status
		$this->db->query("UPDATE " . DB_PREFIX . "product SET switch_status = 1 WHERE product_id = '" . (int)$data['main_product_id'] . "'");
		
	}

	public function editproductswitcher($switch_id, $data) {

		if(!empty($data['productswitcher'])) {
          $headinglabel = serialize($data['productswitcher']);
	    }
	    
		$this->db->query("UPDATE " . DB_PREFIX . "productswitcher SET status = '" . (int)$data['status'] . "', main_product_id = '" . (int)$data['main_product_id'] . "', heading_label = '" . $this->db->escape($headinglabel) . "' WHERE switch_id='".$switch_id."'");
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "productswitcher_product` WHERE switch_id = '" . (int)$switch_id . "'");
		if (!empty($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if(!empty($product_option['switch_label'])) {
                   $label = serialize($product_option['switch_label']);
	             }

	            if(!empty($product_option['product_id'])){ 
				$this->db->query("INSERT INTO " . DB_PREFIX . "productswitcher_product SET 
				switch_id = '" . (int)$switch_id . "',
				main_product_id = '" . (int)$data['main_product_id'] . "', 
				product_id = '" . (int)$product_option['product_id'] . "', 
				sort_order = '" . (int)$product_option['sort_order'] . "',
				label      = '" . $this->db->escape($label) . "',
				image      = '" . $this->db->escape($product_option['image']) . "'"); 
			   }
			    }
		}

	}

	public function deleteproductswitcher($switch_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "productswitcher WHERE switch_id = '" . (int)$switch_id . "'");
		
		$this->db->query("UPDATE " . DB_PREFIX . "product SET switch_status = 0 WHERE product_id = '" . (int)$query->row['main_product_id'] . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "productswitcher` WHERE switch_id = '" . (int)$switch_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "productswitcher_product` WHERE switch_id = '" . (int)$switch_id . "'");
	}

	public function getproductswitcher($switch_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "productswitcher WHERE switch_id = '" . (int)$switch_id . "'");

		return $query->row;
	}

	public function getproductswitchers($data =[]) {
		$sql = "SELECT * FROM " . DB_PREFIX . "productswitcher WHERE switch_id<>0";

		if(!empty($data['filter_product_id'])) {
			$sql .= " AND main_product_id='".(int)$data['filter_product_id']."'";
		}

		$sort_data = [
			'switch_id'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY switch_id";
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

	public function getTotalproductswitchers($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "productswitcher WHERE switch_id<>0";
		
		if(!empty($data['filter_product_id'])) {
			$sql .= " AND main_product_id='".(int)$data['filter_product_id']."'";
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getproductswitcherOptions($switch_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "productswitcher_product pov  WHERE pov.switch_id = '" . (int)$switch_id . "'");
		return $query->rows;
	}

	public function getproductss() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "productswitcher_product WHERE product_id<>0");
		return $query->rows;
	}

	public function getProducts(array $data = []): array {
		$implode = [];
		$prodata = $this->getproductss();
		foreach ($prodata as $key => $value) {
			$implode[] = $value['product_id'];
		}
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ";

		if (!empty($implode)) {
			$sql .= " AND p.product_id NOT IN(" . implode(",", $implode).")";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.`model` LIKE '" . $this->db->escape((string)$data['filter_model'] . '%') . "'";
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

	
	
}

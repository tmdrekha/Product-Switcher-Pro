<?php
class ModelExtensionProductasoption extends Model {

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."productasoption` (
		  `switch_id` int(11) NOT NULL AUTO_INCREMENT,
		  `main_product_id` int(11) NOT NULL,
		  `heading_label` text NOT NULL,
		  `status` int(11) NOT NULL,
		  PRIMARY KEY (`switch_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."productasoption_product` (
			`switchproduct_id` int(11) NOT NULL AUTO_INCREMENT,
			`main_product_id` int(11) NOT NULL,
			`switch_id` int(11) NOT NULL,
			`product_id` int(11) NOT NULL,
			`sort_order` int(11) NOT NULL,
			`label` text NOT NULL,
			`image` text NOT NULL,
			PRIMARY KEY (`switchproduct_id`)
		  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    
		$this->db->query("ALTER TABLE ".DB_PREFIX."product ADD IF NOT EXISTS `switch_status` INT NOT NULL AFTER `status`;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."productasoption`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."productasoption_product`");
		$this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."switch_status");
        $this->db->query("ALTER TABLE ".DB_PREFIX."product DROP IF EXISTS switch_status");
	}

	public function addproductasoption($data) {
	    if(!empty($data['productasoption'])) {
           $headinglabel = serialize($data['productasoption']);
	    }
		$this->db->query("INSERT INTO " . DB_PREFIX . "productasoption SET status = '" . (int)$data['status'] . "', main_product_id = '" . (int)$data['main_product_id'] . "', heading_label = '" . $this->db->escape($headinglabel) . "'");

		$switch_id = $this->db->getLastId();
		
		if(isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
	            if(!empty($product_option['switch_label'])) {
                   $label = serialize($product_option['switch_label']);
	             }

                if(!empty($product_option['product_id'])){
				$this->db->query("INSERT INTO " . DB_PREFIX . "productasoption_product SET 
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

	public function editproductasoption($switch_id, $data) {

		if(!empty($data['productasoption'])) {
          $headinglabel = serialize($data['productasoption']);
	    }
	    
		$this->db->query("UPDATE " . DB_PREFIX . "productasoption SET status = '" . (int)$data['status'] . "', main_product_id = '" . (int)$data['main_product_id'] . "', heading_label = '" . $this->db->escape($headinglabel) . "' WHERE switch_id='".$switch_id."'");
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "productasoption_product` WHERE switch_id = '" . (int)$switch_id . "'");
		if (!empty($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if(!empty($product_option['switch_label'])) {
                   $label = serialize($product_option['switch_label']);
	             }
	             
	            if(!empty($product_option['product_id'])){
				$this->db->query("INSERT INTO " . DB_PREFIX . "productasoption_product SET 
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

	public function deleteproductasoption($switch_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "productasoption WHERE switch_id = '" . (int)$switch_id . "'");
		
		$this->db->query("UPDATE " . DB_PREFIX . "product SET switch_status = 0 WHERE product_id = '" . (int)$query->row['main_product_id'] . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "productasoption` WHERE switch_id = '" . (int)$switch_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "productasoption_product` WHERE switch_id = '" . (int)$switch_id . "'");
	}

	public function getproductasoption($switch_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "productasoption WHERE switch_id = '" . (int)$switch_id . "'");

		return $query->row;
	}

	public function getproductasoptions($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "productasoption WHERE switch_id<>0";

		if(!empty($data['filter_product'])) {
			$sql .= " AND main_product_id='".(int)$data['filter_product']."'";
		}

		$sort_data = array(
			'switch_id'
		);

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

	public function getTotalproductasoptions($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "productasoption WHERE switch_id<>0";
		
		if(!empty($data['filter_product'])) {
			$sql .= " AND main_product_id='".(int)$data['filter_product']."'";
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getproductasoptionOptions($switch_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "productasoption_product pov  WHERE pov.switch_id = '" . (int)$switch_id . "'");
		return $query->rows;
	}


  public function getproductss() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "productasoption_product WHERE product_id<>0");
		return $query->rows;
	}

	public function getProducts($data = array()) {

		$implode = [];
		$prodata = $this->getproductss();
		foreach ($prodata as $key => $value) {
			$implode[] = $value['product_id'];
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";


        if (!empty($implode)) {
			$sql .= " AND p.product_id NOT IN(" . implode(",", $implode).")";
		}
		
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
	
}

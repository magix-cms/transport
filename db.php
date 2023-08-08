<?php
class plugins_transport_db {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;
	
    /**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config,array $params = []) {
        $query = '';
        $dateFormat = new component_format_date();

        if ($config['context'] === 'all') {
            switch ($config['type']) {
                case 'pages':
                    $limit = '';
                    if (!empty($config['offset'])) {
                        $limit = ' LIMIT 0, ' . $config['offset'];
                        if (isset($config['page']) && $config['page'] > 1) {
                            $limit = ' LIMIT ' . (($config['page'] - 1) * $config['offset']) . ', ' . $config['offset'];
                        }
                    }

                    $query = "SELECT p.*
						FROM mc_transport AS p " . $limit;

                    if (isset($config['search'])) {
                        $cond = '';
                        if (is_array($config['search']) && !empty($config['search'])) {
                            $nbc = 1;
                            foreach ($config['search'] as $key => $q) {
                                if ($q !== '') {
                                    $cond .= 'AND ';
                                    $p = 'p' . $nbc;
                                    switch ($key) {
                                        case 'id_tr':
                                            $cond .= 'p.' . $key . ' = :' . $p . ' ';
                                            break;
                                        case 'name_tr':
                                            $cond .= 'p.' . $key . ' = :' . $p . ' ';
                                            break;
                                        case 'date_register':
                                            $q = $dateFormat->date_to_db_format($q);
                                            $cond .= "p." . $key . " LIKE CONCAT('%', :" . $p . ", '%') ";
                                            break;
                                    }
                                    $params[$p] = $q;
                                    $nbc++;
                                }
                            }

                            $query = "SELECT p.* FROM mc_transport AS p $cond" . $limit;
                        }
                    }
                    break;
                case 'page':
                    $query = 'SELECT p.*
							FROM mc_transport AS p
							WHERE p.id_tr = :edit';
                    break;
                case 'lastPages':
                    $query = "SELECT p.*
							FROM mc_transport AS p
							ORDER BY p.id_tr DESC
							LIMIT 1";
                    break;
				default:
					return false;
            }

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
        }
		elseif ($config['context'] === 'one') {
            switch ($config['type']) {
                case 'root':
                    $query = 'SELECT * FROM mc_transport ORDER BY id_tr DESC LIMIT 0,1';
                    break;
                case 'page':
                    $query = 'SELECT * FROM mc_transport WHERE `id_tr` = :id_tr';
                    break;
                case 'cartpay_step':
                    $query = 'SELECT * FROM mc_cartpay_transport 
                            WHERE id_buyer = :id_buyer AND id_cart = :id_cart';
                    break;
                case 'cartpay_order':
                    $query = 'SELECT mt.*, mct.*
                            FROM mc_cartpay_transport AS mct
                            LEFT JOIN mc_transport mt on (mct.id_tr = mt.id_tr)
                            WHERE id_buyer = :id_buyer AND id_cart = :id_cart ORDER BY id_cart_tr DESC LIMIT 0,1';
                    break;
                case 'transport_info':
                    $query = 'SELECT * FROM mc_transport WHERE id_tr = :id_tr';
                    break;
                case 'cartOrder':
                    $query = 'SELECT mt.*, mct.*
                            FROM mc_cartpay_transport AS mct
                            LEFT JOIN mc_transport mt on (mct.id_tr = mt.id_tr)
                            WHERE mct.id_cart = :id';
                    break;
				default:
					return false;
            }

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
        }
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert(array $config, array $params = []) {
        switch ($config['type']) {
            case 'page':
                $query = "INSERT INTO mc_transport (price_tr,name_tr,postcode_tr, country_tr, date_register)
                        VALUE (:price_tr, :name_tr, :postcode_tr, :country_tr, NOW())";
                break;
            case 'cartpay':
                $query = "INSERT INTO mc_cartpay_transport (id_tr, id_cart, id_buyer, type_ct, lastname_ct, firstname_ct, street_ct, city_ct, postcode_ct, event_ct, delivery_date_ct, timeslot_ct, date_register)
                        VALUE (:id_tr, :id_cart, :id_buyer, :type_ct, :lastname_ct, :firstname_ct, :street_ct, :city_ct, :postcode_ct, :event_ct, :delivery_date_ct, :timeslot_ct, NOW())";
                break;
			default:
				return false;
        }

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, array $params = []) {
        switch ($config['type']) {
            case 'page':
                $query = 'UPDATE mc_transport 
						SET 
							name_tr = :name_tr,
						    postcode_tr = :postcode_tr,
						    country_tr = :country_tr,
						    price_tr = :price_tr

                		WHERE id_tr = :id_tr';
                break;
            case 'cartpay':
                $query = 'UPDATE mc_cartpay_transport 
						SET 
							id_tr = :id_tr,
						    type_ct = : type_ct,
						    lastname_ct = :lastname_ct,
						    firstname_ct = :firstname_ct,
						    street_ct = :street_ct,
						    city_ct = :city_ct,
						    postcode_ct = :postcode_ct,
						    event_ct = :event_ct, 
						    delivery_date_ct = :delivery_date_ct, 
						    timeslot_ct = :timeslot_ct

                		WHERE id_buyer = :id_buyer AND id_cart = :id_cart';
                break;
            /*case 'order':
                $query = 'UPDATE mc_transport
						SET order_tr = :order_tr
                		WHERE id_tr = :id_tr';
                break;*/
			default:
				return false;
        }

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete(array $config, array $params = []) {
        switch ($config['type']) {
            case 'delPages':
                $query = 'DELETE FROM mc_transport WHERE id_tr IN ('.$params['id'].')';
                $params = [];
                break;
			default:
				return false;
        }

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
    }
}
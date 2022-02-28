<?php
class plugins_transport_db
{
    /**
     * @param $config
     * @param bool $params
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config, $params = false)
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';
        $dateFormat = new component_format_date();

        if ($config['context'] === 'all') {
            switch ($config['type']) {
                case 'pages':
                    $limit = '';
                    if ($config['offset']) {
                        $limit = ' LIMIT 0, ' . $config['offset'];
                        if (isset($config['page']) && $config['page'] > 1) {
                            $limit = ' LIMIT ' . (($config['page'] - 1) * $config['offset']) . ', ' . $config['offset'];
                        }
                    }

                    $sql = "SELECT p.*
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

                            $sql = "SELECT p.*
						FROM mc_transport AS p $cond" . $limit;
                        }
                    }
                    break;
                case 'page':
                    $sql = 'SELECT p.*
							FROM mc_transport AS p
							WHERE p.id_tr = :edit';
                    break;
                case 'lastPages':
                    $sql = "SELECT p.*
							FROM mc_transport AS p
							ORDER BY p.id_tr DESC
							LIMIT 1";
                    break;
            }

            return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
        }
		elseif ($config['context'] === 'one') {
            switch ($config['type']) {
                case 'root':
                    $sql = 'SELECT * FROM mc_transport ORDER BY id_tr DESC LIMIT 0,1';
                    break;
                case 'page':
                    $sql = 'SELECT * FROM mc_transport WHERE `id_tr` = :id_tr';
                    break;
                case 'cartpay_step':
                    $sql = 'SELECT * FROM mc_cartpay_transport 
                            WHERE id_buyer = :id_buyer AND id_cart = :id_cart';
                    break;
                case 'cartpay_order':
                    $sql = 'SELECT mt.*, mct.*
                            FROM mc_cartpay_transport AS mct
                            LEFT JOIN mc_transport mt on (mct.id_tr = mt.id_tr)
                            WHERE id_buyer = :id_buyer AND id_cart = :id_cart ORDER BY id_cart_tr DESC LIMIT 0,1';
                    break;
                case 'transport_info':
                    $sql = 'SELECT * FROM mc_transport WHERE id_tr = :id_tr';
                    break;
                case 'cartOrder':
                    $sql = 'SELECT mt.*, mct.*
                            FROM mc_cartpay_transport AS mct
                            LEFT JOIN mc_transport mt on (mct.id_tr = mt.id_tr)
                            WHERE mct.id_cart = :id';
                    break;
            }

            return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
        }
    }
    /**
     * @param $config
     * @param array $params
     * @return bool|string
     */
    public function insert($config,$params = array())
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        switch ($config['type']) {
            case 'page':
                $sql = "INSERT INTO mc_transport (price_tr,name_tr,postcode_tr, date_register)
                        VALUE (:price_tr, :name_tr, :postcode_tr, NOW())";
                break;
            case 'cartpay':
                $sql = "INSERT INTO mc_cartpay_transport (id_tr, id_cart, id_buyer, type_ct, lastname_ct, firstname_ct, street_ct, event_ct, delivery_date_ct, timeslot_ct, date_register)
                        VALUE (:id_tr, :id_cart, :id_buyer, :type_ct, :lastname_ct, :firstname_ct, :street_ct, :event_ct, :delivery_date_ct, :timeslot_ct, NOW())";
                break;
        }

        if($sql === '') return 'Unknown request asked';

        try {
            component_routing_db::layer()->insert($sql,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }
    }
    /**
     * @param $config
     * @param array $params
     * @return bool|string
     */
    public function update($config,$params = array())
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        switch ($config['type']) {
            case 'page':
                $sql = 'UPDATE mc_transport 
						SET 
							name_tr = :name_tr,
						    postcode_tr = :postcode_tr,
						    price_tr = :price_tr

                		WHERE id_tr = :id_tr';
                break;
            case 'cartpay':
                $sql = 'UPDATE mc_cartpay_transport 
						SET 
							id_tr = :id_tr,
						    type_ct = : type_ct,
						    lastname_ct = :lastname_ct,
						    firstname_ct = :firstname_ct,
						    street_ct = :street_ct,
						    event_ct = :event_ct, 
						    delivery_date_ct = :delivery_date_ct, 
						    timeslot_ct = :timeslot_ct

                		WHERE id_buyer = :id_buyer AND id_cart = :id_cart';
                break;
            /*case 'order':
                $sql = 'UPDATE mc_transport
						SET order_tr = :order_tr
                		WHERE id_tr = :id_tr';
                break;*/
        }

        if($sql === '') return 'Unknown request asked';

        try {
            component_routing_db::layer()->update($sql,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }
    }
    /**
     * @param $config
     * @param array $params
     * @return bool|string
     */
    public function delete($config, $params = array())
    {
        if (!is_array($config)) return '$config must be an array';
        $sql = '';

        switch ($config['type']) {
            case 'delPages':
                $sql = 'DELETE FROM mc_transport 
						WHERE id_tr IN ('.$params['id'].')';
                $params = array();
                break;
        }

        if($sql === '') return 'Unknown request asked';

        try {
            component_routing_db::layer()->delete($sql,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }
    }
}
<?php
/**
 * Class plugins_attribute_public
 */
class plugins_transport_public extends plugins_transport_db {
    /**
     * @var frontend_model_template $template
     * @var frontend_model_data $data
     * @var component_collections_setting $settingComp
     */
    protected $template;
    protected $data;
	protected $settingComp;
    protected $modelCatalog;

    /**
     * @var int $id
     */
    protected int $id;
    protected $cart;
    protected array $settings;
    public array $contentData;

	/**
	 * @param frontend_model_template|null $t
	 */
    public function __construct(?frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this, $this->template);
        $this->settingComp = new component_collections_setting();
        $this->settings = $this->settingComp->getSetting();

        if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
        if (http_request::isPost('contentData')) $this->contentData = form_inputEscape::arrayClean($_POST['contentData']);
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, ?string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @param array $row
     * @return array
     */
    private function setItemData(array $row): array {
        $country_tr = $this->template->getConfigVars($row['country_tr']);
        $data = [];
        if (!empty($row)) {
            $data['id'] = $row['id_tr'];
            $data['name'] = $row['name_tr'];
            $data['postcode'] = $row['postcode_ct'] ?? null;
            $data['city'] = $row['city_ct'] ?? null;
            $data['country'] = $country_tr;
            $data['price'] = $row['price_tr'];
            $data['lastname'] = $row['lastname_ct'] ?? null;
            $data['firstname'] = $row['firstname_ct'] ?? null;
            $data['street'] = $row['street_ct'] ?? null;
            $data['type'] = $row['type_ct'] ?? null;
            $data['delivery_date'] = $row['delivery_date_ct'] ?? null;
            $data['event'] = $row['event_ct'] ?? null;
            $data['timeslot'] = $row['timeslot_ct'] ?? null;

        }
        return $data;
    }

    /**
     * @return array
     */
    public function getBuildList(): array {
		$newarr = [];
        $collection = $this->getItems('pages',NULL, 'all', false);
        if(!empty($collection)) {
            foreach ($collection as &$item) {
                $newarr[] = $this->setItemData($item);
            }
        }
		return $newarr;
    }

    // --- Cartpay
    /**
     * Update data
     * @param array $data
     */
    private function add(array $data) {
        switch ($data['type']) {
            case 'cartpay':
                parent::insert(
                    ['type' => $data['type']],
                    $data['data']
                );
                break;
        }
    }

    /**
     * Mise a jour des données
     * @param array $data
     */
    private function upd(array $data) {
        switch ($data['type']) {
            case 'cartpay':
                parent::update(
                    ['type' => $data['type']],
                    $data['data']
                );
                break;
        }
    }

    /**
     * Return new cartpay step
     * @return array
     */
    public function setOrderStep(): array {
        return [
            'step' => 'transport_step',
            'pos' => 3,
            'mod' => $this,
            'display' => 'displayOrderStep',
            'process' => 'processOrderStep'
        ];
    }

    /**
     * Cartpay Extension Step
     */
    public function displayOrderStep() {
        $this->template->assign('transport',$this->getBuildList());
        $this->template->display('transport/step.tpl');
    }

    /**
     * Cartpay Extension Step
	 * @param array $cart
     */
    public function processOrderStep(array $cart) {
        //print_r($cart);
        if(isset($this->contentData['type_ct'])) {
            $dateFormat = new date_dateformat();
            $newdata = [
				'id_cart' => $cart['id_cart'],
				'id_buyer' => $cart['id_buyer'],
				'type_ct' => $this->contentData['type_ct'],
				'id_tr' => (!empty($this->contentData['id_tr'])) ? $this->contentData['id_tr'] : NULL,
				'firstname_ct' => (!empty($this->contentData['firstname_ct'])) ? $this->contentData['firstname_ct'] : NULL,
				'lastname_ct' => (!empty($this->contentData['lastname_ct'])) ? $this->contentData['lastname_ct'] : NULL,
				'street_ct' => (!empty($this->contentData['street_ct'])) ? $this->contentData['street_ct'] : NULL,
				'event_ct' => (!empty($this->contentData['event_ct'])) ? $this->contentData['event_ct'] : NULL,
				'timeslot_ct' => (!empty($this->contentData['timeslot_ct'])) ? $this->contentData['timeslot_ct'] : NULL,
				'delivery_date_ct' => (!empty($this->contentData['delivery_date_ct'])) ? $dateFormat->SQLDate($this->contentData['delivery_date_ct']) : NULL,
            	'city_ct' => (!empty($this->contentData['city_ct'])) ? $this->contentData['city_ct'] : NULL,
            	'postcode_ct' => (!empty($this->contentData['postcode_ct'])) ? $this->contentData['postcode_ct'] : NULL
			];

            //Start cart session
            $this->cart = Cart::getInstance('mc_cart');
            $transport = [
				'id_cart' => $cart['id_cart'],
				'id_buyer' => $cart['id_buyer']
			];
            $collection = $this->getItems('cartpay_step', $transport, 'one', false);

            if ($collection != NULL) {
                $this->upd([
					'type' => 'cartpay',
					'data' => $newdata
				]);
            } else {
                $this->add([
					'type' => 'cartpay',
					'data' => $newdata
				]);
                /*$log = new debug_logger(MP_LOG_DIR);
                $log->tracelog(json_encode($newdata));
                $log->tracelog(json_encode(
                        array(
                            'id_cart'=>$cart['id_cart'],
                            'id_buyer'=>$cart['id_buyer'],
                            'id_tr'=>$this->contentData['id_tr']
                        ))
                );*/
            }

            if ($this->contentData['type_ct'] === 'delivery') {
                if (isset($this->contentData['id_tr']) and $this->contentData['id_tr'] != NULL) {
                    $transportData = $this->getItems('transport_info', ['id_tr' => $this->contentData['id_tr']], 'one', false);
                    $this->cart->addFee('transport', $transportData['price_tr'], $this->settings['vat_rate']);
                    //$this->cart->addFee(1,$transportData['price_tr'],21);
                }
            }
        }
    }

    /**
     * @param array $cart
     * @return array
     */
    public function orderResumeInfos(array $cart): array {
        $this->template->addConfigFile(
            [component_core_system::basePath().'/plugins/transport/i18n/'],
            ['public_local_'],
            false
        );
        $transport = [
			'id_cart' => $cart['id_cart'],
			'id_buyer' => $cart['id_buyer']
		];

        $collection = $this->getItems('cartpay_order',$transport, 'one', false);
        $this->template->assign('transport',$this->setItemData($collection));
        $arb = [
            'name'=>$this->template->getConfigVars('transport_step'),
            'desc'=> $this->template->fetch('transport/order.tpl')
        ];
        return $arb;
    }

    /**
     * @param array $cart
     * @return array
     */
    public function mailResumeInfos(array $cart): array {
        $this->template->addConfigFile(
            [component_core_system::basePath().'/plugins/transport/i18n/'],
            ['public_local_'],
            false
        );
        $transport = [
			'id_cart' => $cart['id_cart'],
			'id_buyer' => $cart['id_buyer']
		];

        $collection = $this->getItems('cartpay_order',$transport, 'one', false);
        $this->template->assign('transport',$this->setItemData($collection));
        $arb = [
            'name'=>$this->template->getConfigVars('transport_step'),
            'desc'=> $this->template->fetch('transport/resume-mail.tpl')
        ];
        return $arb;
    }
    // ---- End Cartpay
}
<?php
/**
 * Class plugins_attribute_public
 */
class plugins_transport_public extends plugins_transport_db
{
    /**
     * @var object
     */
    protected $template, $data, $modelCatalog;

    /**
     * @var int $id
     */
    protected $id, $cart, $settingComp, $settings;
    public $contentData;

    /**
     * frontend_controller_home constructor.
     * @param stdClass $t
     */
    public function __construct($t = null)
    {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $formClean = new form_inputEscape();
        $this->data = new frontend_model_data($this, $this->template);
        $this->settingComp = new component_collections_setting();
        $this->settings = $this->settingComp->getSetting();

        if (http_request::isGet('id')) $this->id = $formClean->numeric($_GET['id']);
        if (http_request::isPost('contentData')) $this->contentData = $formClean->arrayClean($_POST['contentData']);
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true)
    {
        return $this->data->getItems($type, $id, $context, $assign);
    }
    /**
     * @param $row
     * @return array
     */
    private function setItemData($row)
    {
        $data = array();
        if ($row != null) {
            $data['id'] = $row['id_tr'];
            $data['name'] = $row['name_tr'];
            $data['postcode'] = $row['postcode_tr'];
            $data['price'] = $row['price_tr'];
            $data['lastname'] = $row['lastname_ct'];
            $data['firstname'] = $row['firstname_ct'];
            $data['street'] = $row['street_ct'];
            $data['type'] = $row['type_ct'];
        }
        return $data;
    }

    /**
     * @return array|null
     */
    public function getBuildList(){
        $collection = $this->getItems('pages',NULL, 'all', false);
        if($collection != null) {
            $newarr = array();
            foreach ($collection as &$item) {
                $newarr[] = $this->setItemData($item);
            }
            return $newarr;
        }else{
            return null;
        }
    }
    // --- Cartpay
    /**
     * Update data
     * @param $data
     * @throws Exception
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'cartpay':
                parent::insert(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }

    /**
     * Mise a jour des données
     * @param $data
     * @throws Exception
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'cartpay':
                parent::update(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }
    /**
     * Return new cartpay step
     * @return array
     */
    public function setOrderStep() {
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
     */
    /**
     * Cartpay Extension Step
     */
    public function processOrderStep($cart) {
        //print_r($cart);
        if(isset($this->contentData['type_ct'])) {
            $dateFormat = new date_dateformat();
            $newdata = array();
            $newdata['id_cart'] = $cart['id_cart'];
            $newdata['id_buyer'] = $cart['id_buyer'];
            // if delivery
            $newdata['type_ct'] = $this->contentData['type_ct'];
            $newdata['id_tr'] = (!empty($this->contentData['id_tr'])) ? $this->contentData['id_tr'] : NULL;
            $newdata['firstname_ct'] = (!empty($this->contentData['firstname_ct'])) ? $this->contentData['firstname_ct'] : NULL;
            $newdata['lastname_ct'] = (!empty($this->contentData['lastname_ct'])) ? $this->contentData['lastname_ct'] : NULL;
            $newdata['street_ct'] = (!empty($this->contentData['street_ct'])) ? $this->contentData['street_ct'] : NULL;
            $newdata['event_ct'] = (!empty($this->contentData['event_ct'])) ? $this->contentData['event_ct'] : NULL;
            $newdata['timeslot_ct'] = (!empty($this->contentData['timeslot_ct'])) ? $this->contentData['timeslot_ct'] : NULL;
            $newdata['delivery_date_ct'] = (!empty($this->contentData['delivery_date_ct'])) ? $dateFormat->SQLDate($this->contentData['delivery_date_ct']) : NULL;

            //Start cart session
            $this->cart = Cart::getInstance('mc_cart');
            $transport = array(
                'id_cart' => $cart['id_cart'],
                'id_buyer' => $cart['id_buyer']
            );
            $collection = $this->getItems('cartpay_step', $transport, 'one', false);

            if ($collection != NULL) {
                $this->upd(array(
                    'type' => 'cartpay',
                    'data' => $newdata
                ));
            } else {
                $this->add(array(
                    'type' => 'cartpay',
                    'data' => $newdata
                ));
                /*$log = new debug_logger(MP_LOG_DIR);
                $log->tracelog(json_encode($newdata));*/
                /*$log->tracelog(json_encode(
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

                    $this->cart->addFee('transport', $transportData['price_tr'], $this->settings['vat_rate']['value']);
                    //$this->cart->addFee(1,$transportData['price_tr'],21);
                }
            }
        }
    }

    /**
     * @param $cart
     * @return array
     */
    public function orderResumeInfos($cart){
        $this->template->addConfigFile(
            [component_core_system::basePath().'/plugins/transport/i18n/'],
            ['public_local_'],
            false
        );
        $transport = array(
            'id_cart'=>$cart['id_cart'],
            'id_buyer'=>$cart['id_buyer']
        );

        $collection = $this->getItems('cartpay_order',$transport, 'one', false);
        $this->template->assign('transport',$this->setItemData($collection));
        $arb = [
            'name'=>$this->template->getConfigVars('transport_step'),
            'desc'=> $this->template->fetch('transport/order.tpl')
        ];
        return $arb;
    }

    /**
     * @param $cart
     * @return array
     */
    public function mailResumeInfos($cart){
        $this->template->addConfigFile(
            [component_core_system::basePath().'/plugins/transport/i18n/'],
            ['public_local_'],
            false
        );
        $transport = array(
            'id_cart'=>$cart['id_cart'],
            'id_buyer'=>$cart['id_buyer']
        );

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
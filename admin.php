<?php
class plugins_transport_admin extends plugins_transport_db
{
    public $edit, $action, $tabs, $search, $plugin, $controller;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $modelPlugins, $routingUrl, $makeFiles, $finder, $plugins, $progress;
    public $id_tr, $content, $pages, $iso, $ajax, $tableaction, $tableform, $offset, $transData, $importData;

    public $tableconfig = array(
        'all' => array(
            'id_tr',
            'name_tr' => array('title' => 'name'),
            'postcode_tr' => array('title' => 'name'),
            'price_tr' => array('type' => 'price','input' => null),
            'date_register'
        )
    );
    /**
     * frontend_controller_home constructor.
     */
    public function __construct($t = null){
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->modelPlugins = new backend_model_plugins();
        $this->routingUrl = new component_routing_url();
        $this->finder = new file_finder();
        // --- GET
        if(http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
        elseif (http_request::isPost('action')) $this->action = $formClean->simpleClean($_POST['action']);
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);
        if (http_request::isGet('ajax')) $this->ajax = $formClean->simpleClean($_GET['ajax']);
        if (http_request::isGet('offset')) $this->offset = intval($formClean->simpleClean($_GET['offset']));

        if (http_request::isGet('tableaction')) {
            $this->tableaction = $formClean->simpleClean($_GET['tableaction']);
            $this->tableform = new backend_controller_tableform($this,$this->template);
        }

        // --- Search
        if (http_request::isGet('search')) {
            $this->search = $formClean->arrayClean($_GET['search']);
            $this->search = array_filter($this->search, function ($value) { return $value !== ''; });
        }

        // --- ADD or EDIT
        if (http_request::isGet('id')) $this->id_tr = $formClean->simpleClean($_GET['id']);
        elseif (http_request::isPost('id')) $this->id_tr = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_lead') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
        if (http_request::isPost('transData')) $this->transData = $formClean->arrayClean($_POST['transData']);
        // --- Recursive Actions
        if (http_request::isGet('transport'))  $this->pages = $formClean->arrayClean($_GET['transport']);
        # ORDER PAGE
        if (http_request::isPost('transport')) $this->order = $formClean->arrayClean($_POST['transport']);
        if (http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);

        # JSON LINK (TinyMCE)
        //if (http_request::isGet('iso')) $this->iso = $formClean->simpleClean($_GET['iso']);
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @param boolean $pagination
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true, $pagination = false) {
        return $this->data->getItems($type, $id, $context, $assign, $pagination);
    }
    /**
     * Method to override the name of the plugin in the admin menu
     * @return string
     */
    public function getExtensionName()
    {
        return $this->template->getConfigVars('transport_plugin');
    }
    /**
     * @param $ajax
     * @return mixed
     * @throws Exception
     */
    public function tableSearch($ajax = false)
    {
        $results = $this->getItems('pages', NULL, 'all',false,true);
        $params = array();

        if($ajax) {
            $params['section'] = 'pages';
            $params['idcolumn'] = 'id_tr';
            $params['activation'] = false;
            $params['sortable'] = true;
            $params['checkbox'] = true;
            $params['edit'] = true;
            $params['dlt'] = true;
            $params['readonly'] = array();
            $params['cClass'] = 'plugins_transport_admin';
        }

        $this->data->getScheme(array('mc_transport'),array('id_tr','name_tr','postcode_tr','price_tr','date_register'),$this->tableconfig['all']);

        return array(
            'data' => $results,
            'var' => 'pages',
            'tpl' => 'index.tpl',
            'params' => $params
        );
    }

    /**
     * Update data
     * @param $data
     * @throws Exception
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'page':
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
     * Mise a jour des donn??es
     * @param $data
     * @throws Exception
     */
    private function upd($data)
    {
        switch ($data['type']) {
            /*case 'order':
                $p = $this->order;
                for ($i = 0; $i < count($p); $i++) {
                    parent::update(
                        array(
                            'type'=>$data['type']
                        ),array(
                            'id_cs'       => $p[$i],
                            'order_cs'    => $i + (isset($this->offset) ? ($this->offset + 1) : 0)
                        )
                    );
                }
                break;*/
            case 'page':
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
     * Insertion de donn??es
     * @param $data
     * @throws Exception
     */
    private function del($data)
    {
        switch($data['type']){
            case 'delPages':
                parent::delete(
                    array(
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
        }
    }
    private function setItemData($row)
    {
        $data = array();
        if ($row != null) {
            $data['lastname'] = $row['lastname_ct'];
            $data['firstname'] = $row['firstname_ct'];
            $data['street'] = $row['street_ct'];
            $data['postcode'] = $row['postcode_tr'];
            $data['name'] = $row['name_tr'];
            $data['price'] = $row['price_tr'];
            $data['type'] = $row['type_ct'];
        }
        return $data;
    }
    /**
     * @param $idcart
     * @return mixed
     */
    public function getTransport($idcart){
        $data = $this->getItems('cartOrder', array('id'=>$idcart), 'one',false,false);
        return $this->setItemData($data);
    }
    /**
     * @throws Exception
     */
    public function run(){
        if(isset($this->tableaction)) {
            $this->tableform->run();
        }
        elseif(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->transData)){
                        $newdata = array();
                        $newdata['name_tr'] = (!empty($this->transData['name_tr'])) ? $this->transData['name_tr'] : NULL;
                        $newdata['postcode_tr'] = (!empty($this->transData['postcode_tr'])) ? $this->transData['postcode_tr'] : NULL;
                        $newdata['price_tr'] = (!empty($this->transData['price_tr'])) ? number_format(str_replace(",", ".", $this->transData['price_tr']), 2, '.', '') : NULL;
                        // Add data
                        $this->add(array(
                            'type' => 'page',
                            'data' => $newdata
                        ));
                        $this->message->json_post_response(true, 'add_redirect');
                    }else{
                        $this->template->display('add.tpl');
                    }
                    break;
                case 'edit':
                    if(isset($this->transData)){
                        $newdata = array();
                        $newdata['id_tr'] = $this->id_tr;
                        $newdata['name_tr'] = (!empty($this->transData['name_tr'])) ? $this->transData['name_tr'] : NULL;
                        $newdata['postcode_tr'] = (!empty($this->transData['postcode_tr'])) ? $this->transData['postcode_tr'] : NULL;
                        $newdata['price_tr'] = (!empty($this->transData['price_tr'])) ? number_format(str_replace(",", ".", $this->transData['price_tr']), 2, '.', '') : NULL;
                        // Add data
                        $this->upd(array(
                            'type' => 'page',
                            'data' => $newdata
                        ));
                        $this->message->json_post_response(true, 'update', $this->transData);
                    }else{
                        $this->getItems('page',array('id_tr'=>$this->edit),'one',true,true);
                        $this->template->display('edit.tpl');
                    }
                    break;
            }
        }else{
            $this->getItems('pages',NULL,'all',true,true);
            $this->data->getScheme(array('mc_transport'),array('id_tr','name_tr','postcode_tr','price_tr','date_register'),$this->tableconfig['all']);
            $this->template->display('index.tpl');
        }
    }
}
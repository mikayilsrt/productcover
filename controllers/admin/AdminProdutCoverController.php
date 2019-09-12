<?php

require _PS_MODULE_DIR_ . '/productCover/classes/Blanket.php';

class AdminProdutCoverController extends ModuleAdminController
{

    public function __construct()
    {
        $this->bootstrap = true;

        $this->table = Blanket::$definition['table'];
        $this->identifier = Blanket::$definition['primary'];
        $this->className = Blanket::class;
        $this->lang = false;

        parent::__construct();

        $this->fields_list = [
            'id_product_cover' => [
                'title' => $this->module->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'image' => [
                'title' =>  $this->module->l('Cover name'),
                'align' =>  'left'
            ]
        ];

        // display action button on each field.
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['new'] = array(
            'href'  =>  self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc'  =>  $this->module->l('Add new cover'),
            'icon'  =>  'process-icon-new'
        );

        parent::initPageHeaderToolbar();
    }

    public function getContent()
    {
        //
    }

    /*
    public function postProcess()
    {
        if((int)Tools::isSubmit('addproduct_cover')) {
        }
    }
    */

    /**
     * Affichage du formulaire d'ajout / création de l'objet
     * @return string
     * @throws SmartyException
     */
    public function renderForm()
    {
        echo "<pre>";
        var_dump($this->getProducts());
        echo "</pre>";
        die();

        $this->fields_form = [
            'legend' => [
                'title' => $this->module->l('Product Cover Form'),
                'icon' => 'icon-cog'
            ],
            'input' => [
                [
                    'type' => 'file',
                    'label' => $this->module->l('Product Cover'),
                    'name' => 'PRODUCT_COVER_IMAGE',
                    'class' => 'input fixed-width-sm',
                    'required' => true,
                    'empty_message' => $this->l('Please fill in the field'),
                ],
                [
                    'type' => 'select',
                    'lang' => true,
                    'label' => $this->l('Product'),
                    'name' => 'PRODUCT_SELECT',
                    'required' => true,
                    'desc' => $this->l('Please select the product to add the covers.'),
                    'options' => [
                        'query' => [],
                        'id' => 'id_option',
                        'name' => 'name',
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ]
        ];
        return parent::renderForm();
    }

    private function getProducts()
    {
        $results = [];
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'product` LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` ON `' . _DB_PREFIX_ . 'product.id_product` = `' . _DB_PREFIX_ . 'product_lang.id_product`';

        $products = Db::getInstance()->executeS($sql);

        foreach($products as $product)
        {
            $results .= [
                'id_option' =>  0,
                'id_product'    =>  $product['id_product'],
                'name'  =>  $product['name']
            ];
        }

        return $results;
    }

}

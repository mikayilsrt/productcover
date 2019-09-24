<?php

require_once _PS_MODULE_DIR_  . '/productcover/classes/Blanket.php';

class AdminProductCoverController extends ModuleAdminController
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
        // $this->addRowAction('edit');
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
        die('getContent');
        $this->postProcess();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddproduct_cover'))
        {
            $productID = Tools::getValue('PRODUCT_SELECT_NAME_ID');
            $productImage = Tools::getValue('PRODUCT_COVER_IMAGE');

            if (!empty($productID) && !empty($productImage))
            {
                $imageFile = $_FILES['PRODUCT_COVER_IMAGE'];

                if (!empty($imageFile['tmp_name']))
                {
                    $allowed = array('gif', 'jpg', 'jpeg', 'png');
                    $path = _PS_PROD_IMG_DIR_ . '../scenes/thumbs';
                    $info = explode('.', strtolower($imageFile['name']));
                    $newName = sha1(microtime()) . str_replace(' ', '', $imageFile['name']);

                    if (in_array(end($info), $allowed))
                    {
                        if (move_uploaded_file($imageFile['tmp_name'], $path . '/' . $newName))
                        {
                            $blanket = new Blanket();
                            $res = $blanket->addNewProductCover($productID, $newName);

                            $this->context->controller->confirmations[] .= $this->l('Cover uploaded.');
                        } else {
                            $this->context->controller->errors[] .= $this->l('Error cover not uploaded');
                        }
                    }
                }
            }
        } elseif (Tools::isSubmit('deleteproduct_cover')) {
            $productCoverId = Tools::getValue('id_product_cover');
            $blanket = new Blanket($productCoverId);
            $res = $blanket->delete();

            if ($res)
            {
                $this->context->controller->confirmations[] .= $this->l('successfully delete.');
            } else {
                $this->context->controller->errors[] .= $this->l('Oups, An error occured');
            }
        }
    }

    /**
     * Affichage du formulaire d'ajout / création de l'objet
     * @return string
     * @throws SmartyException
     */
    public function renderForm()
    {
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
                    'name' => 'PRODUCT_SELECT_NAME_ID',
                    'required' => true,
                    'desc' => $this->l('Please select the product to add the covers.'),
                    'options' => [
                        'query' => $this->getProducts(),
                        'id' => 'id_product',
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

    /**
     * @return array $results
     */
    private function getProducts()
    {
        $results = [];
        $sql = 'SELECT DISTINCT(' . _DB_PREFIX_ . 'product_lang.id_product), ' . _DB_PREFIX_ . 'product_lang.name FROM `' . _DB_PREFIX_ . 'product` LEFT JOIN ' . _DB_PREFIX_ . 'product_lang ON ' . _DB_PREFIX_ . 'product.id_product = ' . _DB_PREFIX_ . 'product_lang.id_product';

        $products = Db::getInstance()->executeS($sql);

        foreach($products as $key => $product)
        {
            array_push($results, [
                'id_option' =>  $key,
                'id_product'    =>  $product['id_product'],
                'name'  =>  $product['name']
            ]);
        }

        return $results;
    }

}

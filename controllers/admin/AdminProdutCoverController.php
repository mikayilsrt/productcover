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

}

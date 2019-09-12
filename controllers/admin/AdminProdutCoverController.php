<?php

class AdminProdutCoverController extends ModuleAdminController
{

    public function __construct()
    {
        parent::__construct();

        $this->bootstrap = true;

        $this->fields_list = [
            'id_product' => [
                'title' => $this->module->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'name' => [
                'title' =>  $this->module->l('name'),
                'align' =>  'left'
            ]
        ];

        // display action button on each field.
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

}

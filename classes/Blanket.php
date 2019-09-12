<?php

class Blanket extends ObjectModel
{

    // public $id_product_cover;
    public $id_product;
    public $image;

    public static $definition = [
        'table' =>  'product_cover',
        'primary'   =>  'id_product_cover',
        'multilang' =>  false,
        'fields'    =>  [
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isGenericName', 'size' => 255, 'required' => true],
            'image' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255, 'required' => true],
        ]
    ];

}

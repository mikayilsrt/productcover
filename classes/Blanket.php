<?php

class Blanket extends ObjectModel
{

    public $id_product_cover;
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

    public function __construct($idProductCover = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($idProductCover, $id_lang, $id_shop);
    }

    public function add($auto_date = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $res = parent::add($auto_date, $null_values);

        $res &= Db::getInstance()->execute("
            INSERT INTO `" . _DB_PREFIX_ . "product_cover` (`id_product`, `image`)
            VALUES($product_id, '" . $image_name . "')"
        );

        var_dump($res);
        die();

        return true;
    }

    public function addNewProductCover($product_id, $image_name)
    {
        $res = Db::getInstance()->execute("
            INSERT INTO `" . _DB_PREFIX_ . "product_cover` (`id_product`, `image`)
            VALUES($product_id, '" . $image_name . "')"
        );

        return $res;
    }

    public function delete()
    {
        return Db::getInstance()->execute('
            DELETE FROM `' . _DB_PREFIX_ . 'product_cover` WHERE `id_product_cover` = ' . (int)$this->id_product_cover
        );
    }

}

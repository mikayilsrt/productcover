<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductCover extends Module
{
    protected $config_form = false;

    public $tabs = array();

    public function __construct()
    {
        $this->name = 'productcover';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mikayil Sert';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->tabs = [
            [
                'name'          =>  'Product Cover',
                'class_name'    =>  'parentProductCover',
                'parent'        =>  ''
            ],
            [
                'name'          =>  'cover',
                'class_name'    =>  'AdminProductCover',
                'parent'        =>  'parentProductCover'
            ]
        ];

        $this->displayName = $this->l('Product Cover');
        $this->description = $this->l('Module to upload others product covers.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('PRODUCTCOVER_LIVE_MODE', false);

        require _PS_MODULE_DIR_ . basename(dirname(__FILE__)) . '/sql/install.php';

        return parent::install() &&
            $this->installModuleTab() &&
            $this->registerHook('header') &&
            $this->registerHook('displayProductCoverImage') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('PRODUCTCOVER_LIVE_MODE');

        require _PS_MODULE_DIR_ . basename(dirname(__FILE__)) . '/sql/uninstall.php';

        return parent::uninstall() &&
            $this->installModuleTab(false);
    }

    /**
     * Install new tab or remove on Dashboard.
     *
     * @param bool $install
     *
     * @return bool Status
     */
    public function installModuleTab ($install = true)
    {
        if ($install)
        {
            $languages = Language::getLanguages();

            foreach ($this->tabs as $t) {
                $tab = new Tab();
                $tab->module = $this->name;
                $tab->class_name = $t['class_name'];
                $tab->id_parent = Tab::getIdFromClassName($t['parent']);

                foreach ($languages as $language) {
                    $tab->name[$language['id_lang']] = $t['name'];
                }
                $tab->save();
            }
        } else {
            foreach ($this->tabs as $t) {
                $id = Tab::getIdFromClassName($t['class_name']);
                if ($id) {
                    $tab = new Tab($id);
                    $tab->delete();
                }
            }
        }

        return true;
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Display the product cover image by product id.
     *
     * @param array $params
     *
     * @return file hook file.
     */
    public function hookDisplayProductCoverImage($params)
    {
        $this->context->smarty->assign(
            'product_cover',
            $this->getProductCover($params['id_customer'])
        );

        return $this->display(__FILE__, 'productcover.tpl');
    }

    /**
     * Get all product cover uploaded by product_id
     *
     * @param int $product_id
     *
     * @return Array $list of product cover.
     */
    public function getProductCover($product_id)
    {
        $coverArray = [];
        $product_covers = Db::getInstance()->executeS('
            SELECT image as product_cover
            FROM ' . _DB_PREFIX_ . 'product_cover
            WHERE id_product = ' . $product_id
        );

        foreach ($product_covers as $key => $product_cover) {
            array_push(
                $coverArray,
                [
                    'key'           =>  $key,
                    'cover_image'   =>  $product_cover['product_cover'],
                ]
            );
        }

        return $coverArray;
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}

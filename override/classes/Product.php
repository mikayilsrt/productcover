<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class Product extends ProductCore
{
    /**
     * Get product images and legends.
     *
     * @param int $id_lang Language id for multilingual legends
     *
     * @return array Product images and legends
     */
    /*
    * module: productCover
    * date: 2019-09-15 17:23:42
    * version: 1.0.0
    */
    public function getImages($id_lang, Context $context = null)
    {
        return Db::getInstance()->executeS(
            '
            SELECT image_shop.`cover`, i.`id_image`, il.`legend`, i.`position`, pc.image as product_cover_image
            FROM `' . _DB_PREFIX_ . 'image` i
            ' . Shop::addSqlAssociation('image', 'i') . '
            LEFT JOIN `' . _DB_PREFIX_ . 'product_cover` pc ON (i.id_product = pc.id_product)
            LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang . ')
            WHERE i.`id_product` = ' . (int) $this->id . '
            ORDER BY `position`'
        );
    }

    /**
     * Get product cover image.
     *
     * @return array Product cover image
     */
    /*
    * module: productCover
    * date: 2019-09-15 17:23:42
    * version: 1.0.0
    */
    public static function getCover($id_product, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }
        $cache_id = 'Product::getCover_' . (int) $id_product . '-' . (int) $context->shop->id;
        if (!Cache::isStored($cache_id)) {
            $sql = 'SELECT image_shop.`id_image`
                    FROM `' . _DB_PREFIX_ . 'image` i
                    ' . Shop::addSqlAssociation('image', 'i') . '
                    WHERE i.`id_product` = ' . (int) $id_product . '
                    AND image_shop.`cover` = 1';
            $result = Db::getInstance()->getRow($sql);
            Cache::store($cache_id, $result);
            return $result;
        }
        return Cache::retrieve($cache_id);
    }
}

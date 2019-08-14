<?php

class Edrone_Base_Block_Product extends Edrone_Base_Block_Base
{
    /**
     * @return array
     */
    public function getProductData()
    {
        $productArray = array();
        $product = Mage::registry('current_product');

        $productArray['sku'] = $product->getSku();
        $productArray['title'] = $product->getName();
        $productArray['image'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438);

        return $productArray;
    }
}
<?php

class Edrone_Base_Block_Cart extends Edrone_Base_Block_Base
{
    /**
     * @return array
     */
    public function getProductData()
    {
        $productData = array();
        $product = Mage::getModel('core/session')->getProductToShoppingCart();

        if ($product && $product->getSku()) {
            $productData['sku'] = $product->getSku();
            $productData['title'] = $product->getTitle();
            $productData['image'] = $product->getImage();

            Mage::getModel('core/session')->unsProductToShoppingCart();
        }

        return $productData;
    }
}
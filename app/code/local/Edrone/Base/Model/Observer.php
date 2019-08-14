<?php

class Edrone_Base_Model_Observer
{
    public function addToCart()
    {
        $product = Mage::getModel('catalog/product')
            ->load(Mage::app()->getRequest()->getParam('product', 0));

        if (!$product->getId()) {
            return;
        }

        Mage::getModel('core/session')->setProductToShoppingCart(
            new Varien_Object(array(
                'sku'   => $product->getSku(),
                'title' => $product->getName(),
                'image' => (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438),
            ))
        );
    }
}
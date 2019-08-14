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
            $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
            if(count($parentIds) > 0){
                $product = Mage::getModel("catalog/product")->load( $parentIds[0] );
                $title = $product->getName();
                $image = (string) Mage::helper('catalog/image')->init($product, 'image')->resize(438);
            }else{
                $title = $product->getTitle();
                $image = $product->getImage();
            }
            
            $productData['sku'] = $product->getSku();
            $productData['id'] = intval( Mage::getModel("catalog/product")->getIdBySku( $product->getSku() ) );
            $productData['title'] = $title;
            $productData['image'] = $image;
            
            
            $_Product = Mage::getModel("catalog/product")->load( $productData['id']  );
            $categoryIds = $_Product->getCategoryIds();//array of product categories
            $categoryId = array_pop($categoryIds);
            

            if(is_numeric($categoryId)){
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $productData['product_category_names'] = $category->getName();
                $productData['product_category_ids'] = $categoryId; 
            } 

            Mage::getModel('core/session')->unsProductToShoppingCart();
        }

        return $productData;
    }
}
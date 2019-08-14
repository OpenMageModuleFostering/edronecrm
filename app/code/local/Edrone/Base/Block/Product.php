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
        $productArray['id'] = $product->getId();
        $productArray['title'] = $product->getName();
        $productArray['image'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438);
        
        $categoryIds = $product->getCategoryIds();//array of product categories

        
        $categoryId = array_pop($categoryIds);
        if(is_numeric($categoryId)){
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $productArray['product_category_names'] = $category->getName();
            $productArray['product_category_ids'] = $categoryId; 
        }      
        return $productArray;
    }
}
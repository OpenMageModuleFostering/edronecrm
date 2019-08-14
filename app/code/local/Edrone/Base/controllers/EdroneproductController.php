<?php

class Edrone_Base_EdroneproductController extends Mage_Core_Controller_Front_Action {
    public function indexAction(){
        
    }
    public function skuAction(){
        $sku = Mage::app()->getRequest()->getParam('v');
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
        
        $productArray['sku'] = $product->getSku();
        $productArray['id'] = $product->getId();
        $productArray['title'] = $product->getName();
        $productArray['image'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438);
        $productArray['product_url'] = $product->getUrl();
        
        $categoryIds = $product->getCategoryIds();//array of product categories


        
        $categoryId = array_pop($categoryIds);
        if(is_numeric($categoryId)){
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $productArray['product_category_names'] = $category->getName();
            $productArray['product_category_ids'] = $categoryId; 
            
        }     
       
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($productArray));
    }
    public function idAction(){
        $id = Mage::app()->getRequest()->getParam('v');
        $product = Mage::getModel('catalog/product')->load($id);
        
        $productArray['sku'] = $product->getSku();
        $productArray['id'] = $product->getId();
        $productArray['title'] = $product->getName();
        $productArray['image'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438);
        $productArray['product_url'] = $product->getUrl();
        
        $categoryIds = $product->getCategoryIds();//array of product categories


        
        $categoryId = array_pop($categoryIds);
        if(is_numeric($categoryId)){
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $productArray['product_category_names'] = $category->getName();
            $productArray['product_category_ids'] = $categoryId; 
            
        }     
       
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($productArray));
    }
}

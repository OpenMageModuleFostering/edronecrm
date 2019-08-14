<?php

class Edrone_Base_Block_Order extends Edrone_Base_Block_Base
{
    /**
     * @return array
     */
    public function getOrderData()
    {
        $orderData = $skus = $titles = $images = array();

        $lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($lastOrderId);

        $product_category_names = array();
        $product_category_ids = array();
        
        
        foreach ($order->getAllVisibleItems() as $item) {
            $skus[] = $item->getSku();
            $ids[] = $item->getId();
            $titles[] = $item->getName();

            $_Product = Mage::getModel("catalog/product")->load( $item->getId() );
            $categoryIds = $_Product->getCategoryIds();//array of product categories
            $categoryId = array_pop($categoryIds);
            if(is_numeric($categoryId)){
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $product_category_names[] = $category->getName();
                $product_category_ids[]   = $categoryId; 
            } 
            
            
            $product = $item->getProduct();
            if ($product) $images[] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438);
        }

        $orderData['sku'] = join('|', $skus);
        $orderData['id'] = join('|', $ids);
        $orderData['title'] = join('|', $titles);
        $orderData['image'] = join('|', $images);
        $orderData['order_id'] = $order->getIncrementId();
        $orderData['order_payment_value'] = $order->getGrandTotal();
        $orderData['base_payment_value'] = $order->getBaseGrandTotal();
        $orderData['base_currency'] = $order->getBaseCurrencyCode();
        $orderData['order_currency'] = $order->getOrderCurrencyCode();
        $orderData['coupon'] = $order->getCouponCode();
        $orderData['product_category_names']  = join('|',$product_category_names);
        $orderData['product_category_ids']    = join('|',$product_category_ids);

        return $orderData;
    }

    public function getCustomerData()
    {
        parent::getCustomerData();

            $lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($lastOrderId);

            $this->customerData['first_name'] = $order->getBillingAddress()->getFirstname();
            $this->customerData['last_name'] = $order->getBillingAddress()->getLastname();
            $this->customerData['email'] = $order->getBillingAddress()->getEmail();
            $this->customerData['country'] = $order->getBillingAddress()->getCountry();
            $this->customerData['city'] = $order->getBillingAddress()->getCity();
            $this->customerData['phone'] = $order->getBillingAddress()->getTelephone();

        return $this->customerData;
    }
}
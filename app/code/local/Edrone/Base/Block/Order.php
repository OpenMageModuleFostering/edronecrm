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
        $product_counts       = array();
        
        foreach ($order->getAllVisibleItems() as $item) {
            $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($item->getProductId());
            if(count($parentIds) > 0){
                $product = Mage::getModel("catalog/product")->load( $parentIds[0] );
                $skus[] = $product->getSku();
                $ids[] = $product->getId();
                $titles[] = $product->getName();
                $categoryIds = $product->getCategoryIds();//array of product categories
                $product_counts[] = (int)$item->getQtyOrdered();
                $categoryId = array_pop($categoryIds);
                if(is_numeric($categoryId)){
                    $category = Mage::getModel('catalog/category')->load($categoryId);
                    $product_category_names[] = $category->getName();
                    $product_category_ids[]   = $categoryId; 

                } 
                $images[] = ($product) ? (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438) : '';
            }else{
                $product = Mage::getModel("catalog/product")->load( $item->getProductId() );
                $skus[] = $product->getSku();
                $ids[] = $product->getId();
                $titles[] = $product->getName();  
                $categoryIds = $product->getCategoryIds();//array of product categories
                $product_counts[] = (int)$item->getQtyOrdered();
                $categoryId = array_pop($categoryIds);
                if(is_numeric($categoryId)){
                    $category = Mage::getModel('catalog/category')->load($categoryId);
                    $product_category_names[] = $category->getName();
                    $product_category_ids[]   = $categoryId; 

                } 
                $images[] = ($product) ? (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438) : '';
            }
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
        $orderData['product_counts'] = join('|',$product_counts);

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
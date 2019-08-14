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
                    'sku' => $product->getSku(),
                    'title' => $product->getName(),
                    'image' => (string) Mage::helper('catalog/image')->init($product, 'image')->resize(438),
                    'id' => $product->getId(),
                ))
        );
    }

    private function getOrderData($order)
    {

        $orderData = array();
        $customerData = array();
        $product_category_names = array();
        $product_category_ids = array();
        $product_counts = array();

        $m_product_type_configurable = Mage::getModel('catalog/product_type_configurable');
        $m_product  = Mage::getModel("catalog/product");
        $m_category = Mage::getModel('catalog/category');
        
        foreach ($order->getAllVisibleItems() as $item) {
            $parentIds = $m_product_type_configurable->getParentIdsByChild($item->getProductId());
            if(count($parentIds) > 0){
                $product = $m_product->load( $parentIds[0] );
                $skus[] = $product->getSku();
                $ids[] = $product->getId();
                $titles[] = $product->getName();
                $categoryIds = $product->getCategoryIds();//array of product categories
                $product_counts[] = (int)$item->getQtyOrdered();
                $categoryId = array_pop($categoryIds);
                if(is_numeric($categoryId)){
                    $category = $m_category->load($categoryId);
                    $product_category_names[] = $category->getName();
                    $product_category_ids[]   = $categoryId; 

                } 
                $images[] = ($product) ? (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438) : '';
            }else{
                $product = $m_product->load( $item->getProductId() );
                $skus[] = $product->getSku();
                $ids[] = $product->getId();
                $titles[] = $product->getName();  
                $categoryIds = $product->getCategoryIds();//array of product categories
                $product_counts[] = (int)$item->getQtyOrdered();
                $categoryId = array_pop($categoryIds);
                if(is_numeric($categoryId)){
                    $category = $m_category->load($categoryId);
                    $product_category_names[] = $category->getName();
                    $product_category_ids[]   = $categoryId; 

                } 
                $images[] = ($product) ? (string)Mage::helper('catalog/image')->init($product, 'image')->resize(438) : '';
            }
        }
        $orderData['order_id'] = $order->getIncrementId();
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
        $orderData['product_category_names'] = join('|', $product_category_names);
        $orderData['product_category_ids'] = join('|', $product_category_ids);
        $orderData['product_counts'] = join('|', $product_counts);

        $customerData['first_name'] = $order->getBillingAddress()->getFirstname();
        $customerData['last_name'] = $order->getBillingAddress()->getLastname();
        $customerData['email'] = $order->getBillingAddress()->getEmail();
        $customerData['country'] = $order->getBillingAddress()->getCountry();
        $customerData['city'] = $order->getBillingAddress()->getCity();
        $customerData['phone'] = $order->getBillingAddress()->getTelephone();

        return array($orderData, $customerData);
    }

    private function sendDataToServer($orderData, $customerData)
    {
        try {
            $configHelper = Mage::helper('edrone/config');
            if(!$configHelper->isSSSync()){ return; };
            $edrone = new Edrone_Base_Model_Utilities_EdroneIns($configHelper->getAppId(), '');
            $edrone->setCallbacks(function($obj) {
                Mage::log("EDRONEPHPSDK ERROR - wrong request:" . json_encode($obj->getLastRequest()));
            }, function() {
                
            });
            $edrone->prepare(
                    Edrone_Base_Model_Utilities_EdroneEventOrder::create()->
                            userFirstName(($customerData['first_name']))->
                            userLastName(($customerData['last_name']))->
                            userEmail($customerData['email'])->
                            productSkus($orderData['sku'])->
                            productTitles($orderData['title'])->
                            productImages($orderData['image'])->
                            productCategoryIds($orderData['product_category_ids'])->
                            productCategoryNames($orderData['product_category_names'])->
                            orderId($orderData['order_id'])->
                            orderPaymentValue($orderData['order_payment_value'])->
                            orderCurrency($orderData['order_currency'])->
                            productCounts($orderData['product_counts'])
            )->send();
        } catch (Exception $e) {
            Mage::log("EDRONEPHPSDK ERROR:" . $e->getMessage() . ' more :' . json_encode($e));
        }
        return json_encode($edrone->getLastRequest());
    }

    public function export_new_order($observer)
    {
        $order = $observer->getEvent()->getOrder();
        $orderdata = $this->getOrderData($order);
        $status = $this->sendDataToServer($orderdata[0], $orderdata[1]);
        return $this;
    }

    public function newsletterSubscriberChange($observer)
    {
       
        $subscriber = $observer->getEvent()->getSubscriber();
        $sub = 0;
        if ($subscriber->isSubscribed()) {
            $sub = 1;
            $email = $subscriber->getEmail();
            try {
                $configHelper = Mage::helper('edrone/config');
                if(!$configHelper->isSSSync()){ return; };
                $edrone = new Edrone_Base_Model_Utilities_EdroneIns($configHelper->getAppId(), '');
                $edrone->setCallbacks(function($obj) {
                    Mage::log("EDRONEPHPSDK ERROR - wrong request:" . json_encode($obj->getLastRequest()));
                }, function($obj) {
                });
                $edrone->prepare(
                        Edrone_Base_Model_Utilities_EdroneEventSubscribe::create()->
                                userEmail($email)->
                                userSubscriberStatus($sub)                      
                )->send();
            } catch (Exception $e) {
                Mage::log("EDRONEPHPSDK ERROR:" . $e->getMessage() . ' more :' . json_encode($e));
            }
        }
        
        
    }

}

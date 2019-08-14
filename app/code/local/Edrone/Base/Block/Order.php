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

        foreach ($order->getAllVisibleItems() as $item) {
            $skus[] = $item->getSku();
            $titles[] = $item->getName();
            $images[] = (string)Mage::helper('catalog/image')->init($item->getProduct(), 'image')->resize(438);
        }

        $orderData['sku'] = join('|', $skus);
        $orderData['title'] = join('|', $titles);
        $orderData['image'] = join('|', $images);
        $orderData['order_id'] = $order->getIncrementId();
        $orderData['order_payment_value'] = $order->getGrandTotal();
        $orderData['base_payment_value'] = $order->getBaseGrandTotal();
        $orderData['base_currency'] = $order->getBaseCurrencyCode();
        $orderData['order_currency'] = $order->getOrderCurrencyCode();
        $orderData['coupon'] = $order->getCouponCode();


        return $orderData;
    }

    public function getCustomerData()
    {
        parent::getCustomerData();

        if (empty($this->customerData['first_name'])) {
            $lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($lastOrderId);

            $this->customerData['first_name'] = $order->getBillingAddress()->getFirstname();
            $this->customerData['last_name'] = $order->getBillingAddress()->getLastname();
            $this->customerData['email'] = $order->getBillingAddress()->getEmail();
            $this->customerData['country'] = $order->getBillingAddress()->getCountry();
            $this->customerData['city'] = $order->getBillingAddress()->getCity();
        }

        return $this->customerData;
    }
}
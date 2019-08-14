<?php

class Edrone_Base_Block_Base extends Mage_Core_Block_Template
{

    /**
     * @var Edrone_Base_Helper_Config
     */
    private $configHelper;

    /**
     * @var array
     */
    protected $customerData = array();

    public function _construct()
    {
        parent::_construct();

        $this->configHelper = Mage::helper('edrone/config');
    }

    /**
     * @return Edrone_Base_Helper_Config
     */
    public function getConfigHelper()
    {
        return $this->configHelper;
    }

    /**
     * @return array
     */
    public function getCustomerData()
    {
        if(!count($this->customerData)) {
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->getLoggedCustomerData();
            } else {
                $this->getGuestCustomerData();
            }
        }

        return $this->customerData;
    }

    private function getLoggedCustomerData()
    {
        /** @var Mage_Customer_Model_Customer $customer */
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $this->customerData['first_name'] = $customer->getFirstname();
        $this->customerData['last_name'] = $customer->getLastname();
        $this->customerData['email'] = $customer->getEmail();

        if ($address = $customer->getDefaultShippingAddress()) {
            $this->customerData['country'] = $address->getCountry();
            $this->customerData['city'] = $address->getCity();
            $this->customerData['phone'] = $address->getTelephone();
        } else {
            $this->customerData['country'] = '';
            $this->customerData['city'] = '';
            $this->customerData['phone'] = '';
        }

        $this->customerData['is_logged_in'] = 1;
    }

    private function getGuestCustomerData()
    {
        $this->customerData['first_name'] = '';
        $this->customerData['last_name'] = '';
        $this->customerData['email'] = '';
        $this->customerData['country'] = '';
        $this->customerData['city'] = '';
        $this->customerData['phone'] = '';

        $quote = Mage::getModel('checkout/cart')->getQuote();
        $address = $quote->getBillingAddress();

        if($address) {
            $this->customerData['first_name'] = $address->getFirstname();
            $this->customerData['last_name'] = $address->getFirstname();
            $this->customerData['email'] = $address->getFirstname();
            $this->customerData['country'] = $address->getFirstname();
            $this->customerData['city'] = $address->getFirstname();
            $this->customerData['phone'] = $address->getTelephone();
        }

        $this->customerData['is_logged_in'] = 0;


    }
}
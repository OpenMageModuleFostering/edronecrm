<?php

class Edrone_Base_Block_Base extends Mage_Core_Block_Template
{
    const CUSTOMER_DATA_KEY = 'customerData';

    /**
     * @var Edrone_Base_Helper_Config
     */
    private $helper;

    /**
     * @var array
     */
    protected $customerData = array();

    public function __construct()
    {
        $this->helper = Mage::helper('edrone/config');
        $this->customerData = Mage::registry(self::CUSTOMER_DATA_KEY);
    }

    /**
     * @return Edrone_Base_Helper_Config
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return array
     */
    public function getCustomerData()
    {
        if (empty($this->customerData)) {
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->getLoggedCustomerData();
            } else {
                $this->getGuestCustomerData();
            }

            Mage::register(self::CUSTOMER_DATA_KEY, $this->customerData);
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
        } else {
            $this->customerData['country'] = '';
            $this->customerData['city'] = '';
        }
    }

    private function getGuestCustomerData()
    {
        $this->customerData['first_name'] = '';
        $this->customerData['last_name'] = '';
        $this->customerData['email'] = '';
        $this->customerData['country'] = '';
        $this->customerData['city'] = '';
    }
}
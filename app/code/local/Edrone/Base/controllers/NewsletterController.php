<?php

class Edrone_Base_NewsletterController extends Mage_Core_Controller_Front_Action {
    public function unsubscribeAction()
    {

        $helper = Mage::helper('edrone');
        $configHelper = Mage::helper('edrone/config');

        if(!$configHelper->isNewsletterSyncEnabled()) {
            $this->getResponse()->setBody('1');
            return;
        }

        $token = $this->getRequest()->getParam('token');
        $email = $this->getRequest()->getParam('email');


        if($email && $token && $helper->validateToken($email, $token)) {

            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
            if($subscriber->getId()) {
                $subscriber->unsubscribe();
                $this->getResponse()->setBody('0');
                return;
            }
            $this->getResponse()->setBody('3');
            return;

        } else {
            $this->getResponse()->setBody('2');
            return;
        }
    }
}
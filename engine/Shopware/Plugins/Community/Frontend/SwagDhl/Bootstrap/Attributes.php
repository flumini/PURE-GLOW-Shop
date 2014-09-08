<?php

namespace Shopware\SwagDhl\Bootstrap;

class Attributes
{
    public function create()
    {
        $this->createOrderAttributes();

        $this->createDispatchAttributes();

        $this->createShippingAddressAttributes();

        $this->generateAttributeModels();
    }

    public function createOrderAttributes()
    {
        Shopware()->Models()->addAttribute(
            's_order_attributes',
            'swag',
            'dhl_address',
            'text'
        );

	    Shopware()->Models()->addAttribute(
		    's_order_attributes',
		    'swag',
		    'dhl_order_info',
		    'text'
	    );
    }

    public function createDispatchAttributes()
    {
        Shopware()->Models()->addAttribute(
            's_premium_dispatch_attributes',
            'swag',
            'dhl_dispatch',
            'tinyint(1)'
        );

        Shopware()->Models()->addAttribute(
            's_premium_dispatch_attributes',
            'swag',
            'dhl_new_installation',
            'tinyint(1)'
        );
    }

    public function createShippingAddressAttributes()
    {
        Shopware()->Models()->addAttribute(
            's_user_shippingaddress_attributes',
            'swag',
            'dhl_packstation',
            'text'
        );

        Shopware()->Models()->addAttribute(
            's_user_shippingaddress_attributes',
            'swag',
            'dhl_postoffice',
            'text'
        );

	    Shopware()->Models()->addAttribute(
		    's_user_shippingaddress_attributes',
		    'swag',
		    'dhl_postnumber',
		    'int(10)'
	    );
    }

    public function generateAttributeModels()
    {
        Shopware()->Models()->generateAttributeModels(
            array(
                's_order_attributes',
                's_premium_dispatch_attributes',
                's_user_shippingaddress_attributes'
            )
        );
    }
}
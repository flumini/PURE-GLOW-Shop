<?php

namespace Shopware\SwagDhl\Subscriber;

class Register implements \Enlight\Event\SubscriberInterface
{
	public function __construct(\Shopware_Plugins_Frontend_SwagDhl_Bootstrap $bootstrap)
	{
		$this->bootstrap = $bootstrap;
	}

	public static function getSubscribedEvents()
	{
		return array(
			'Enlight_Controller_Action_PostDispatchSecure_Frontend_Register' => 'onPostDispatchRegister',
			'Enlight_Controller_Action_PostDispatchSecure_Frontend_Account' => 'onPostDispatchRegister',
			'Shopware_Modules_Admin_UpdateShippingAttributes_FilterSql' => 'onUpdateShipping',
			'sAdmin::sSaveRegisterShipping::after' => 'afterRegister');
	}

	public function onPostDispatchRegister(\Enlight_Event_EventArgs $args)
	{
		/* @var \Enlight_Controller_Action $subject */
		$subject = $args->getSubject();
		$view = $subject->View();

		$view->addTemplateDir($this->bootstrap->Path() . "Views/");
		$view->extendsTemplate('frontend/swag_dhl/register/shipping_fieldset.tpl');
	}
	
	public function onUpdateShipping(\Enlight_Event_EventArgs $args)
	{
		$attributeData = $args->getReturn();
		$user = $args->getUser();

		$shipping = $user['register']['shipping'];
		if($shipping['postnumber']) {
			$attributeData[0]['swag_dhl_postnumber'] = $shipping['postnumber'];
		} else {
			$attributeData[0]['swag_dhl_postnumber'] = null;
		}

		$args->setReturn($attributeData);
	}

	public function afterRegister(\Enlight_Hook_HookArgs $args)
	{
		$shippingId = $args->getReturn();
		$params = Shopware()->Front()->Request()->getParams();
		$shippping = $params['register']['shipping'];

		/** @var \Shopware\Models\Attribute\CustomerShipping $attribute */
		$attribute = Shopware()->Models()->getRepository('Shopware\Models\Attribute\CustomerShipping')->findOneBy(array('customerShippingId' => $shippingId));

		$attribute->setSwagDhlPostnumber($shippping['postnumber']);
		Shopware()->Models()->persist($attribute);
		Shopware()->Models()->flush();

		$args->setReturn($shippingId);
	}
} 
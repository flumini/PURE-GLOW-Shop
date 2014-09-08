<?php

namespace Shopware\SwagDhl\Subscriber;

class Backend implements \Enlight\Event\SubscriberInterface
{
	protected $bootstrap;

	public function __construct(\Shopware_Plugins_Frontend_SwagDhl_Bootstrap $bootstrap)
	{
		$this->bootstrap = $bootstrap;
	}

	public static function getSubscribedEvents()
	{
		return array(
			'Enlight_Controller_Action_PostDispatchSecure_Backend_Index' => 'onPostDispatchIndex',
			'Enlight_Controller_Action_PreDispatch_Backend_Shipping' => 'onPreDispatchShipping',
			'Enlight_Controller_Action_PostDispatchSecure_Backend_Shipping' => 'onPostDispatchShipping');
	}

	/**
	 * provides the DHL logo in the backend
	 *
	 * @param \Enlight_Event_EventArgs $args
	 */
	public function onPostDispatchIndex(\Enlight_Event_EventArgs $args)
	{
		/* @var \Enlight_Controller_Action $subject */
		$subject = $args->getSubject();
		$view = $subject->View();

		$view->addTemplateDir($this->bootstrap->Path() . "Views/");
		$view->extendsTemplate('backend/dhl/menu_entry.tpl');
	}

	/**
	 * workaround for shopware versions which do not support dispatch attributes properly
	 *
	 * @param \Enlight_Event_EventArgs $args
	 */
	public function onPreDispatchShipping(\Enlight_Event_EventArgs $args)
	{
		/* @var \Enlight_Controller_Action $subject */
		$subject = $args->getSubject();
		$request = $subject->Request();

		if($request->getActionName() !== 'updateDispatch') {
			return;
		} else {
			$attribute = $request->getParam('attribute', array());
			if(array_key_exists(0, $attribute)) {
				$attribute = $attribute[0];
				$request->setParam('attribute', $attribute);
			}
		}
	}

	/**
	 * extends the standard dispatch attribute with a value
	 * that indicates the dispatch as DHL dispatch
	 *
	 * @param \Enlight_Event_EventArgs $args
	 */
	public function onPostDispatchShipping(\Enlight_Event_EventArgs $args)
	{
		/* @var \Enlight_Controller_Action $subject */
		$subject = $args->getSubject();
		$request = $subject->Request();
		$view = $subject->View();

		$view->addTemplateDir($this->bootstrap->Path() . 'Views/');

		if($request->getActionName() === 'load') {
			$view->extendsTemplate('backend/shipping/model/dhl_attribute.js');
		}
	}
}
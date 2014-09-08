<?php

namespace Shopware\SwagDhl\Subscriber;

class ControllerPath implements \Enlight\Event\SubscriberInterface
{
	protected $bootstrap;

	public function __construct(\Shopware_Plugins_Frontend_SwagDhl_Bootstrap $bootstrap)
	{
		$this->bootstrap = $bootstrap;
	}

	public static function getSubscribedEvents()
	{
		return array(
			'Enlight_Controller_Dispatcher_ControllerPath_Frontend_Dhl' => 'onGetDhlControllerPath',
			'Enlight_Controller_Dispatcher_ControllerPath_Backend_Dhl' => 'onGetDhlControllerPath');
	}

	/**
	 * @param \Enlight_Event_EventArgs $arguments
	 * @return string
	 *
	 */
	public function onGetDhlControllerPath(\Enlight_Event_EventArgs $arguments)
	{
		Shopware()->Template()->addTemplateDir($this->bootstrap->Path() . 'Views');

		switch($arguments->getName()) {
			case 'Enlight_Controller_Dispatcher_ControllerPath_Backend_Dhl':
				return $this->bootstrap->Path() . 'Controllers/Backend/Dhl.php';
			case 'Enlight_Controller_Dispatcher_ControllerPath_Frontend_Dhl':
				return $this->bootstrap->Path() . 'Controllers/Frontend/Dhl.php';
		}
	}
}
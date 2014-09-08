<?php
/**
 * Copyright © 2014 shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 *
 * @category   Shopware
 * @package    Shopware_Plugins
 * @subpackage Notification
 * @copyright  shopware AG (http://www.shopware.de)
 * @version    $Id$
 * @author     $Author$
 */

/**
 * Shopware DHL Integration Plugin
 *
 * Allows customers to choose from an extended shipping options
 * Includes a frontend module to select the mode of shipping during the checkout process
 * Includes a backend module that display statistics about the orders placed with DHL shipping and process them
 *
 */
class Shopware_Plugins_Frontend_SwagDhl_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
	/**
	 * Installation of plugin
	 * Registers all the events required for the plugin
	 * @return bool
	 */
	public function install()
	{
		$attributes = new \Shopware\SwagDhl\Bootstrap\Attributes();
		$attributes->create();
		$this->createEvents();
		$this->createConfiguration();
		$this->createMenuEntry();
		$this->createNewDispatch();

		return true;
	}

	public function afterInit()
	{
		$this->Application()->Loader()->registerNamespace('Shopware\SwagDhl', $this->Path());
	}

	private function createMenuEntry()
	{
		$this->createMenuItem(array(
			'label' => 'DHL',
			'controller' => 'Dhl',
			'class' => 'dhl-icon',
			'action' => 'Index',
			'active' => 1,
			'parent' => $this->Menu()->findOneBy(array('label' => 'Kunden'))));
	}

	/**
	 * Will register the DispatchLoopStartup event - all other events will be handled in event subscribers
	 */
	private function createEvents()
	{
		$this->subscribeEvent('Enlight_Controller_Front_DispatchLoopStartup', 'onStartDispatch');
	}

	/**
	 * This callback function is triggered at the very beginning of the dispatch process and allows
	 * us to register additional events on the fly. This way you won't ever need to reinstall you
	 * plugin for new events - any event and hook can simply be registered in the event subscribers
	 */
	public function onStartDispatch(Enlight_Event_EventArgs $args)
	{
		$subscribers = array(
			new \Shopware\SwagDhl\Subscriber\ControllerPath($this),
			new \Shopware\SwagDhl\Subscriber\Backend($this),
			new \Shopware\SwagDhl\Subscriber\Checkout($this),
			new \Shopware\SwagDhl\Subscriber\Resources($this),
			new \Shopware\SwagDhl\Subscriber\Register($this));

		foreach($subscribers as $subscriber) {
			$this->Application()->Events()->addSubscriber($subscriber);
		}
	}

	private function createNewDispatch()
	{
		/* @var \Shopware\Models\Attribute\Dispatch $dispatchAttribute */
		$dispatchAttribute = Shopware()->Models()->getRepository('Shopware\Models\Attribute\Dispatch')->findOneBy(array('swagDhlDispatch' => 1));
		$dispatchModel = null;
		if($dispatchAttribute) {
			$dispatchModel = $dispatchAttribute->getDispatch();
		}

		if(!$dispatchModel) {
			$dispatchModel = new Shopware\Models\Dispatch\Dispatch();
			$dispatchModel->setType(0);
			$dispatchModel->setName('DHL Versand');
			$dispatchModel->setDescription('');
			$dispatchModel->setComment('DHL');
			$dispatchModel->setActive(0);
			$dispatchModel->setPosition(15);
			$dispatchModel->setCalculation(1);
			$dispatchModel->setStatusLink('<a href="http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc={$offerPosition.trackingcode}" target="_blank">DHL Tracking</a>');
			$dispatchModel->setSurchargeCalculation(0);
			$dispatchModel->setTaxCalculation(0);
			$dispatchModel->setBindLastStock(0);
			$dispatchModel->setBindShippingFree(0);
			$dispatchAttribute = new \Shopware\Models\Attribute\Dispatch();
			$dispatchAttribute->setSwagDhlDispatch(1);
			$dispatchAttribute->setSwagDhlNewInstallation(1);
            // Prevent possible issues with old bepado versions. fromArray will not throw an exception if
            // the column 'bepadoAllowed' doesn't exist - so we are using that here
            $dispatchAttribute->fromArray(array(
                'bepadoAllowed' => 0
            ));
			$dispatchModel->setAttribute($dispatchAttribute);

			Shopware()->Models()->persist($dispatchModel);
			Shopware()->Models()->flush();
		}

		$shippingCosts = Shopware()->Models()->getRepository('Shopware\Models\Dispatch\ShippingCost')->findBy(array('dispatchId' => $dispatchModel->getId()));
		if(!$shippingCosts) {
			$shippingCost = new Shopware\Models\Dispatch\ShippingCost();
			$shippingCost->setFrom('0');
			$shippingCost->setValue(1);
			$shippingCost->setFactor(0);
			$shippingCost->setDispatch($dispatchModel);

			Shopware()->Models()->persist($shippingCost);
			Shopware()->Models()->flush();
		}
	}

	public function getVersion()
	{
		return '1.0.3';
	}

	public function getLabel()
	{
		return 'DHL Integration';
	}

	public function getInfo()
	{
		return array(
			'version' => $this->getVersion(),
			'label' => $this->getLabel(),
			'name' => $this->getLabel(),
			'link' => 'http://www.shopware.de',
			'description' => 'Shopware DHL Integration');
	}

	/**
	 * Create Configuration Method
	 *
	 * Creates configuration form for all necessary details required for DHL SOAP calls
	 */
	public function createConfiguration()
	{
		$form = new Shopware\SwagDhl\Bootstrap\Form();
		$form->create($this->Form());
	}

	/**
	 * Uninstall function can be uncommented on need
	 */
	public function uninstall()
	{
		$dhlModel = Shopware()->Models()->getRepository('Shopware\Models\Dispatch\Dispatch')->findOneBy(array('comment' => 'DHL'));
		if($dhlModel) {
			$dhlModel->setActive(0);

			Shopware()->Models()->persist($dhlModel);
			Shopware()->Models()->flush();
		}
		return true;
	}
}

<?php

namespace Shopware\SwagDhl\Subscriber;

use Shopware\SwagDhl\Components\Converter\PackstationToAddress;
use Shopware\SwagDhl\Components\Converter\PostOfficeToAddress;
use Shopware\SwagDhl\Components\ShippingDetection;
use Shopware\SwagDhl\Components\VerificationService;
use Shopware\SwagDhl\Structs\Address;
use Shopware\SwagDhl\Structs\OrderInfo;

class Checkout implements \Enlight\Event\SubscriberInterface
{
	protected $bootstrap;

	public function __construct(\Shopware_Plugins_Frontend_SwagDhl_Bootstrap $bootstrap)
	{
		$this->bootstrap = $bootstrap;
	}

	public static function getSubscribedEvents()
	{
		return array(
			'Enlight_Controller_Action_PreDispatch_Frontend_Checkout' => 'verifyDhlDataBeforeCheckout',
			'Enlight_Controller_Action_PostDispatchSecure_Frontend_Checkout' => 'onPostDispatchCheckout',
			'sOrder::sSaveOrder::after' => 'afterOrder');
	}

	public function getShippingDetection()
	{
		return new ShippingDetection(Shopware()->Models(), Shopware()->Session()->sDispatch);
	}

	public function getVerificationService()
	{
		return new VerificationService();
	}

	public function verifyDhlDataBeforeCheckout(\Enlight_Event_EventArgs $arguments)
	{
		/* @var \Enlight_Controller_Action $subject */
		$subject = $arguments->getSubject();
		$request = $subject->Request();

		if($request->getActionName() != 'finish') {
			return;
		}

		if(!$this->getShippingDetection()->isDhlShippingSelected()) {
			return;
		}

		$userData = Shopware()->Modules()->Admin()->sGetUserData();
		$dhlDispatch = Shopware()->Session()->dhlDispatch;

		$message = null;

		switch($dhlDispatch) {
			case 'postoffice':
				try {
					$postnumber = $userData['shippingaddress']['swagDhlPostnumber'];
					if(!$postnumber) {
						$message = 'postnumber';
					}
					$postOffice = unserialize($userData['shippingaddress']['swagDhlPostoffice']);
					if($postOffice) {
						$this->getVerificationService()->verify($postOffice);
					} else {
						$message = 'postoffice';
					}
				}
				catch(\RuntimeException $e) {
					$message = $e->getMessage();
				}
				break;
			case 'packstation':
				try {
					$postnumber = $userData['shippingaddress']['swagDhlPostnumber'];
					if(!$postnumber) {
						$message = 'postnumber';
					}
					$packstation = unserialize($userData['shippingaddress']['swagDhlPackstation']);
					if($packstation) {
						$this->getVerificationService()->verify($packstation);
					} else {
						$message = 'packstation';
					}
				}
				catch(\RuntimeException $e) {
					$message = $e->getMessage();
				}
				break;
			case 'default_dhl':
			default:
				break;
		}

		if($message) {
			Shopware()->Session()->dhlErrorMessage = $message;
			$subject->forward('confirm');
		}
	}

	public function onPostDispatchCheckout(\Enlight_Event_EventArgs $arguments)
	{
		/* @var \Enlight_Controller_Action $subject */
		$subject = $arguments->getSubject();
		$request = $subject->Request();
		$view = $subject->View();

		if($request->getActionName() != "confirm") {
			return;
		}

		$view->assign('dhlErrorMessage', Shopware()->Session()->dhlErrorMessage);
		$view->extendsTemplate('frontend/swag_dhl/checkout/dhl_error_message.tpl');
		unset(Shopware()->Session()->dhlErrorMessage);

		$user = Shopware()->Modules()->Admin()->sGetUserData();
		$view->packStation = unserialize($user['shippingaddress']['swagDhlPackstation']);
		$view->postOffice = unserialize($user['shippingaddress']['swagDhlPostoffice']);
		$view->postNumber = $user['shippingaddress']['swagDhlPostnumber'];

		/* @var \Shopware\Models\Attribute\Dispatch $dispatchAttribute */
		$dispatchAttributes = Shopware()->Models()->getRepository('Shopware\Models\Attribute\Dispatch')->findBy(array('swagDhlDispatch' => 1));
		$dhlDispatchIds = array();
		if($dispatchAttributes) {
			foreach($dispatchAttributes as $dispatchAttribute) {
				$dhlDispatchIds[] = $dispatchAttribute->getDispatch()->getId();
			}
		}

		$view->addTemplateDir($this->bootstrap->Path() . "Views/");
		$view->extendsTemplate("frontend/swag_dhl/index/header.tpl");

		$userId = $user["additional"]["user"]["id"];

		$builder = Shopware()->Models()->createQueryBuilder();
		$builder->select('customer', 'shipping', 'attribute')
				->from('Shopware\Models\Customer\Customer', 'customer')
				->innerJoin('customer.shipping', 'shipping')
				->leftJoin('shipping.attribute', 'attribute')
				->where('customer.id = :userId')
				->setParameter('userId', $userId);

		$query = $builder->getQuery();
		$result = $query->getArrayResult();

		$view->sUserId = $userId;
		$view->shippingData = $result[0]['shipping']['attribute'];

		if(!empty($dhlDispatchIds)) {
			$view->dhlDispatchIds = $dhlDispatchIds;
			$view->showPostOffice = $this->bootstrap->Config()->get('showPostOffice');
			$view->showPackStation = $this->bootstrap->Config()->get('showPackStation');
			$view->extendsTemplate('frontend/swag_dhl/checkout/confirm_dispatch.tpl');
		}

		$view->dhlDispatch = Shopware()->Session()->dhlDispatch;
	}

	/**
	 * Initiates a shipment when the order is placed with DHL shipping method
	 * and generates the label for the order.
	 * @param \Enlight_Hook_HookArgs $arguments
	 */
	public function afterOrder(\Enlight_Hook_HookArgs $arguments)
	{
		$orderNumber = $arguments->getReturn();
		/** @var \Shopware\Models\Order\Order $orderModel */
		$orderModel = Shopware()->Models()->getRepository('Shopware\Models\Order\Order')->findOneBy(array('number' => $orderNumber));
		$selectedDispatch = $orderModel->getDispatch();

		/* @var \Shopware\Models\Attribute\Dispatch $attributeModel */
		$attributeModels = Shopware()->Models()->getRepository('Shopware\Models\Attribute\Dispatch')->findBy(array('swagDhlDispatch' => 1));
		$selectedDispatchIsDhl = false;
		if($attributeModels) {
			foreach($attributeModels as $attributeModel) {
				if($selectedDispatch->getId() == $attributeModel->getDispatch()->getId()) {
					$selectedDispatchIsDhl = true;
				}
			}
		}

		if($selectedDispatchIsDhl) {
			/** @var \Shopware\Models\Customer\Customer $customerModel */
			$customerModel = $orderModel->getCustomer();
			$shippingModel = $customerModel->getShipping();

			if($shippingModel) {
				$shippingAttribute = $shippingModel->getAttribute();
			} else {
				$billingModel = $customerModel->getBilling();

				$shippingModel = new \Shopware\Models\Customer\Shipping;
				$shippingModel->setCompany($billingModel->getCompany());
				$shippingModel->setDepartment($billingModel->getDepartment());
				$shippingModel->setSalutation($billingModel->getSalutation());
				$shippingModel->setFirstName($billingModel->getFirstName());
				$shippingModel->setLastName($billingModel->getLastName());
				$shippingModel->setStreet($billingModel->getStreet());
				$shippingModel->setStreetNumber($billingModel->getStreetNumber());
				$shippingModel->setZipCode($billingModel->getZipCode());
				$shippingModel->setCity($billingModel->getCity());
				$shippingModel->setCountryId($billingModel->getCountryId());
				$shippingModel->setStateId($billingModel->getStateId());

				$customerModel->setShipping($shippingModel);
				Shopware()->Models()->persist($customerModel);

				$shippingAttribute = new \Shopware\Models\Attribute\CustomerShipping;

				$shippingModel->setAttribute($shippingAttribute);
				Shopware()->Models()->persist($shippingModel);
				Shopware()->Models()->flush();
			}

			$weight = $this->calculateWeight($orderModel);
			$isBulkfreight = $this->checkIfBulkfreight($orderModel);

			$firstName = $shippingModel->getFirstName();
			$lastName = $shippingModel->getLastName();

			$identifier = Shopware()->Session()->dhlDispatch;
			switch($identifier) {
				case 'postoffice':
					$postOfficeConverter = new PostOfficeToAddress();
					$address = $postOfficeConverter->convert(unserialize($shippingAttribute->getSwagDhlPostoffice()));
					break;
				case 'packstation':
					$packstationConverter = new PackstationToAddress();
					$address = $packstationConverter->convert(unserialize($shippingAttribute->getSwagDhlPackstation()));
					break;
				case 'default_dhl':
				default:
					/* @var \Shopware\Models\Country\Country $country */
					$country = Shopware()->Models()->getRepository('Shopware\Models\Country\Country')->find($shippingModel->getCountryId());
					$address = new Address(array(
						'street' => $shippingModel->getStreet(),
						'streetNumber' => $shippingModel->getStreetNumber(),
						'city' => $shippingModel->getCity(),
						'zip' => $shippingModel->getZipCode(),
						'country' => $country->getName()));
					break;
			}

			$address->firstName = $firstName;
			$address->lastName = $lastName;
			$address->postNumber = $shippingModel->getAttribute()->getSwagDhlPostnumber();

			$orderInfo = new OrderInfo();
			$orderInfo->weight = $weight;
			$orderInfo->isBulkfreight = $isBulkfreight;
			$orderInfo->identifier = $identifier;

			$orderShipping = $orderModel->getShipping();
			$orderShipping->setStreet($address->street);
			$orderShipping->setStreetNumber($address->streetNumber);
			$orderShipping->setZipCode($address->zip);
			$orderShipping->setCity($address->city);

			$orderAttribute = $orderModel->getAttribute();
			$orderAttribute->setSwagDhlAddress(serialize($address));
			$orderAttribute->setSwagDhlOrderInfo(serialize($orderInfo));

			Shopware()->Models()->persist($orderShipping);
			Shopware()->Models()->persist($orderAttribute);
			Shopware()->Models()->flush();
		}

		$arguments->setReturn($orderNumber);
	}

	private function calculateWeight(\Shopware\Models\Order\Order $orderModel)
	{
		/** @var \Shopware\Models\Order\Detail $detail */
		$details = $orderModel->getDetails();

		// 0.1 is the minimum weight for dhl, so it can't be set to 0
		$totalWeight = 0.1;

		foreach($details as $detail) {
			if($detail->getMode() != 0) {
				continue;
			}
			/* @var \Shopware\Models\Article\Detail $articleDetail */
			$articleDetail = Shopware()->Models()->getRepository('Shopware\Models\Article\Detail')->findOneBy(array('number' => $detail->getArticleNumber()));
			$totalWeight += $articleDetail->getWeight() * $detail->getQuantity();
		}

		if($totalWeight > 0.1) {
			$totalWeight -= 0.1;
		}

		if($totalWeight < 0.1) {
			$totalWeight = 0.1;
		}

		return str_replace(',', '.', $totalWeight);
	}

	private function checkIfBulkfreight(\Shopware\Models\Order\Order $orderModel)
	{
		$isBulkfreight = 0;
		/** @var \Shopware\Models\Order\Detail $detail */
		$details = $orderModel->getDetails();

		foreach($details as $detail) {
			if($detail->getMode() != 0) {
				continue;
			}
			/* @var \Shopware\Models\Article\Detail $articleDetail */
			$articleDetail = Shopware()->Models()->getRepository('Shopware\Models\Article\Detail')->findOneBy(array('number' => $detail->getArticleNumber()));

			if($articleDetail->getWidth() > 60) {
				$isBulkfreight = 1;
			}

			if($articleDetail->getHeight() > 60) {
				$isBulkfreight = 1;
			}

			if($articleDetail->getLen() > 120) {
				$isBulkfreight = 1;
			}
		}

		return $isBulkfreight;
	}
}
<?php


namespace Shopware\SwagDhl\Components;

use Shopware\Components\Model\ModelManager;
use Shopware\Models\Attribute\CustomerShipping;
use Shopware\Models\Customer\Shipping;
use Shopware\SwagDhl\Structs\PackStation;
use Shopware\SwagDhl\Structs\PostOffice;

class ShippingPersister
{
	/** @var  ModelManager */
	protected $em;

	public function __construct(ModelManager $em)
	{
		$this->em = $em;
	}

	public function persist($userId, $identifier, $postNumber, $street, $streetNumber, $city, $zip, $number)
	{
		$model = $this->getOrCreateShippingAttribute($userId);

		if($identifier == 'postoffice') {
			$postOffice = new PostOffice(array(
				'street' => $street,
				'streetNumber' => $streetNumber,
				'city' => $city,
				'zip' => $zip,
				'officeNumber' => $number));
			$model->setSwagDhlPostoffice(serialize($postOffice));
			$model->setSwagDhlPostnumber($postNumber);
		} else {
			$packStation = new PackStation(array(
				'street' => $street,
				'streetNumber' => $streetNumber,
				'city' => $city,
				'zip' => $zip,
				'stationNumber' => $number));
			$model->setSwagDhlPackstation(serialize($packStation));
			$model->setSwagDhlPostnumber($postNumber);
		}

		$this->em->persist($model);
		$this->em->flush();
	}

	/**
	 * Will return the shipping attribute for the given user id. If the attribute / the shipping model do not exist,
	 * they will be created
	 *
	 * @param $userId
	 * @return null|object|CustomerShipping
	 */
	public function getOrCreateShippingAttribute($userId)
	{
		/** @var $customer \Shopware\Models\Customer\Customer */
		$customer = $this->em->find('Shopware\Models\Customer\Customer', $userId);
		$shippingModel = $customer->getShipping();

		if(!$shippingModel) {
			$billingModel = $customer->getBilling();
			$billingData = $this->em->toArray($billingModel);
			$shippingModel = new Shipping();
			$shippingModel->fromArray($billingData);
			$shippingModel->setCustomer($customer);

			$this->em->persist($shippingModel);
			$this->em->flush();
		}

		$shippingId = $shippingModel->getId();

		$model = $this->em->getRepository('Shopware\Models\Attribute\CustomerShipping')->findOneBy(array('customerShippingId' => $shippingId));
		if(!$model) {
			$model = new CustomerShipping();
			$model->setCustomerShippingId($shippingId);
			return $model;
		}
		return $model;
	}
}
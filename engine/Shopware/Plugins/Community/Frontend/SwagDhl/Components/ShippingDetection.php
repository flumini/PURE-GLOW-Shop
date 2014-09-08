<?php

namespace Shopware\SwagDhl\Components;

use Shopware\Components\Model\ModelManager;

class ShippingDetection
{
	protected $em;
	protected $currentShippingId;

	public function __construct(ModelManager $em, $currentShippingId)
	{
		$this->em = $em;
		$this->currentShippingId = $currentShippingId;
	}

	public function isDhlShippingSelected()
	{
		/* @var \Shopware\Models\Attribute\Dispatch $attributeModel*/
		$attributeModel = $this->em->getRepository('Shopware\Models\Attribute\Dispatch')->findOneBy(array('swagDhlDispatch' => 1));

		if(!$attributeModel) {
			return false;
		}

		$dhlId = $attributeModel->getDispatch()->getId();
		return $dhlId == $this->currentShippingId;
	}
}
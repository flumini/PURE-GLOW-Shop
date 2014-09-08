<?php

namespace Shopware\SwagDhl\Components\Converter;

use Shopware\SwagDhl\Structs\Address;
use Shopware\SwagDhl\Structs\PostOffice;

class PostOfficeToAddress implements ConverterInterface
{
	/**
	 * @param $struct PostOffice
	 * @return Address
	 */
	public function convert($struct)
	{
		$address = new Address();
		$address->city = $struct->city;
		$address->zip = $struct->zip;
		$address->street = 'Postoffice';
		$address->streetNumber = $struct->officeNumber;
		$address->country = "Deutschland";

		return $address;
	}
}
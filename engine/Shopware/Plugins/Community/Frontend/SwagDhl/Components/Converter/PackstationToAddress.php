<?php

namespace Shopware\SwagDhl\Components\Converter;

use Shopware\SwagDhl\Structs\Address;
use Shopware\SwagDhl\Structs\PackStation;

class PackstationToAddress implements ConverterInterface
{
	/**
	 * @param $struct PackStation
	 * @return Address
	 */
	public function convert($struct)
	{
		$address = new Address();
		$address->city = $struct->city;
		$address->zip = $struct->zip;
		$address->street = 'Packstation';
		$address->streetNumber = $struct->stationNumber;
		$address->country = "Deutschland";

		return $address;
	}
}
<?php

namespace Shopware\SwagDhl\Structs\Verificator;

use Shopware\SwagDhl\Structs\Base;

class PackStation implements VerificatorInterface
{
	public function verify(Base $struct)
	{
		$required = array(
			'stationNumber',
			'city',
			'zip',
			'street',
			'streetNumber');

		foreach($required as $field) {
			if(!$struct->$field) {
				throw new \RuntimeException("{$field}");
			}
		}

		return true;
	}
}
<?php

namespace Shopware\SwagDhl\Components;

/**
 * Service class for verifying structs
 *
 * Class VerificationService
 * @package Shopware\SwagDhl\Components
 */
class VerificationService
{
	public function verify($struct)
	{
		$reflectionClass = new \ReflectionClass($struct);
		$class = $reflectionClass->getShortName();

		$verificatorClass = "\\Shopware\\SwagDhl\\Structs\\Verificator\\{$class}";

		/** @var \Shopware\SwagDhl\Structs\Verificator\VerificatorInterface $verificator */
		$verificator = new $verificatorClass();
		$verificator->verify($struct);
	}
}
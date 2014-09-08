<?php
namespace Shopware\SwagDhl\Structs\Verificator;

use Shopware\SwagDhl\Structs\Base;

interface VerificatorInterface
{
	public function verify(Base $struct);
}
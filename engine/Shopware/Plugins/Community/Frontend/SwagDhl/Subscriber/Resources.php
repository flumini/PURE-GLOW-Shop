<?php

namespace Shopware\SwagDhl\Subscriber;

use Shopware\SwagDhl\Components\Api\SwagDhlFactory;

class Resources implements \Enlight\Event\SubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'Enlight_Bootstrap_InitResource_SwagDhlFactory' => 'onInitResourceSwagDhlFactory',);
	}

	public function onInitResourceSwagDhlFactory()
	{
		return new SwagDhlFactory();
	}
}
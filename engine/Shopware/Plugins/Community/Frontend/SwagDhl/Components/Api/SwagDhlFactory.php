<?php


namespace Shopware\SwagDhl\Components\Api;

class SwagDhlFactory
{
	protected $versandClient;
	protected $soapClient;

	public function getVersandClient()
	{
		if(!$this->versandClient) {
			$this->versandClient = new DhlVersandClient();
		}
		return $this->versandClient;
	}

	public function getSoapClient()
	{
		if(!$this->soapClient) {
			$this->soapClient = new DhlSoapClient('oshoexeanofir');
		}
		return $this->soapClient;
	}
}
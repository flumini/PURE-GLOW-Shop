<?php

namespace Shopware\SwagDhl\Components\Api;

use Shopware\SwagDhl\Helpers\ApiClasses\PostfinderRequestBuilder;
use Shopware\SwagDhl\Helpers\WebServiceImplService;

class DhlSoapClient
{
	protected $dhlKey;

	public function __construct($dhlKey)
	{
		$this->dhlKey = $dhlKey;
	}

	private function getDhlKey()
	{
		return $this->dhlKey;
	}

	/**
	 * Dhl Client Resource method
	 * Parameters are passed in from plugin configuration
	 * call the runGetPackstationsByAddress method and returns the packstations from DHL response
	 * @param $uCity
	 * @param $uPlz
	 * @return string
	 */
	public function getPackstations($uCity, $uPlz)
	{
		$responseDhl = $this->runGetPackstationsByAddress($uCity, $uPlz);
		return $responseDhl;
	}

	/**
	 * getPostoffices Resource method to get the postoffices
	 * Parameters are passed in from plugin configuration
	 * call the runGetPostofficeByAddress method and returns the postoffices from DHL response
	 * @param $uCity
	 * @param $uPlz
	 * @return string
	 */
	public function getPostoffices($uCity, $uPlz)
	{
		$responseDhl = $this->runGetPostofficeByAddress($uCity, $uPlz);
		return $responseDhl;
	}

	/**
	 * makes DHL SOAP call to get the packstations
	 * @param $userCity
	 * @param $userZip
	 * @return string
	 */
	public function runGetPackstationsByAddress($userCity, $userZip)
	{
		$street = "";
		$streetNo = "";
		$zip = $userZip;
		$city = $userCity;

		$parameters = array("street" => $street, "streetNo" => $streetNo, "zip" => $zip, "city" => $city);
		$pfRequestBuilder = new PostfinderRequestBuilder();
		$getPackstationsByAddr = $pfRequestBuilder->getPackstationsByAddr($this->getDhlKey(), $parameters);
		$port = new WebServiceImplService();

		try {
			$getPackstationsByAddrResponse = $port->getPackstationsByAddress($getPackstationsByAddr);
			$packstations = $getPackstationsByAddrResponse->packstation;
		}
		catch(\Exception $e) {
			return $e->getMessage();
		}
		return $packstations;
	}

	/**
	 * makes SOAP call to get all the postoffices
	 * @param $userCity
	 * @param $userZip
	 * @return string
	 */
	public function runGetPostofficeByAddress($userCity, $userZip)
	{
		$street = "";
		$streetNo = "";
		$zip = $userZip;
		$city = $userCity;

		$parameters = array("street" => $street, "streetNo" => $streetNo, "zip" => $zip, "city" => $city);
		$pfRequestBuilder = new PostfinderRequestBuilder();
		$getBranchesByAddr = $pfRequestBuilder->getBranchesByAddr($this->getDhlKey(), $parameters);
		$port = new WebServiceImplService();

		try {
			$getBranchesByAddressResponse = $port->getBranchesByAddress($getBranchesByAddr);
			$postoffices = $getBranchesByAddressResponse->branch;
		}
		catch(\Exception $e) {
			return $e->getMessage();
		}
		return $postoffices;
	}
}
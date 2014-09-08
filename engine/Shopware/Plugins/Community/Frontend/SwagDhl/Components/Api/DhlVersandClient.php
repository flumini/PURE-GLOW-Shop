<?php

namespace Shopware\SwagDhl\Components\Api;

require_once 'DhlBusinessClientLibrary/Geschaeftskundenversand/GeschaeftskundenversandRequestBuilder.php';

class DhlVersandClient
{
	private $gkvRequestBuilder = null;

	private function getSoapClient()
	{
		return new \SoapClient("https://cig.dhl.de/cig-wsdls/com/dpdhl/wsdl/geschaeftskundenversand-api/1.0/geschaeftskundenversand-api-1.0.wsdl", array(
			'login' => 'SwagDHL1_1',
			'password' => 'VmpCiD8OaZomt25wGhlyb2ElsQlfyj',
			'location' => 'https://cig.dhl.de/services/production/soap',
			'soap_version' => \SOAP_1_1));
	}

	private function getTestSoapClient()
	{
		return new \SoapClient("https://cig.dhl.de/cig-wsdls/com/dpdhl/wsdl/geschaeftskundenversand-api/1.0/geschaeftskundenversand-api-1.0.wsdl", array(
			'login' => 'rs',
			'password' => 'dhlZugang123.',
			'location' => 'https://cig.dhl.de/services/sandbox/soap',
			'soap_version' => \SOAP_1_1));
	}

	private function getSoapHeader($intraAuthentication)
	{
		$authentication = new \AuthentificationType($intraAuthentication["user"], $intraAuthentication["password"], NULL, "0");
		return new \SoapHeader('http://dhl.de/webservice/cisbase', 'Authentification', $authentication);
	}

	private function getGkvRequestBuilder()
	{
		if(!$this->gkvRequestBuilder) {
			return new \GeschaeftskundenversandRequestBuilder();
		} else {
			return $this->gkvRequestBuilder;
		}
	}

	/**
	 * main function to pass customized shipment details to DHL
	 * All the details are necessary for label generation
	 *
	 * @param $shipper
	 * @param $receiver
	 * @param $intraAuthentication
	 * @param orderInfo
	 * @param $postNumber
	 * @param $printOnlyIfCodeable
	 * @return \Exception|string
	 */
	public function createShipment($shipper, $receiver, $intraAuthentication, $orderInfo, $postNumber, $printOnlyIfCodeable)
	{
		$intraShip = $this->getSoapClient();
		$authHeader = $this->getSoapHeader($intraAuthentication);
		$intraShip->__setSoapHeaders($authHeader);

		try {
			$ddRequest = $this->getGkvRequestBuilder()->createDefaultShipmentDDRequest($shipper, $receiver, $orderInfo, $postNumber, $printOnlyIfCodeable);

			$shResponse = $intraShip->__soapCall('createShipmentDD', array($ddRequest));

			$crState = $shResponse->CreationState;
			$status = $shResponse->status;

			if($status->StatusCode != 0) {
				$labelUrl = null;
				$shipmentNumber = null;
				$warning = $status->StatusCode;
				$statusMessage = $status->StatusMessage;
				if($crState) {
					$warning = $crState->StatusCode;
					$statusMessage = $crState->StatusMessage;
				}
			} else {
				$labelUrl = $crState->Labelurl;
				$shipmentNumber = $crState->ShipmentNumber->shipmentNumber;
				$warning = $crState->StatusCode != 0;
				$statusMessage = $crState->StatusMessage;
			}

			return array(
				'label' => $labelUrl,
				'shipmentNumber' => $shipmentNumber,
				'warning' => $warning,
				'message' => $statusMessage);
		}
		catch(\Exception $e) {
			return array('message' => $e->getMessage(), 'error' => true);
		}
	}

	public function getLabel($intraAuth, $shippingNumber)
	{
		$intraShip = $this->getSoapClient();
		$authHeader = $this->getSoapHeader($intraAuth);
		$intraShip->__setSoapHeaders($authHeader);

		$getLabelDDRequest = $this->getGkvRequestBuilder()->getDefaultLabelDDRequest($shippingNumber);

		$response = $intraShip->__soapCall('getLabelDD', array($getLabelDDRequest));

		return $response;
	}

	public function deleteShipment($intraAuth, $shippingNumber)
	{
		$intraShip = $this->getSoapClient();
		$authHeader = $this->getSoapHeader($intraAuth);
		$intraShip->__setSoapHeaders($authHeader);

		$deleteShipmentRequest = $this->getGkvRequestBuilder()->getDeleteShipmentDDRequest($shippingNumber);

		$shResponse = $intraShip->__soapCall("DeleteShipmentDD", array($deleteShipmentRequest));

		return array('error' => $shResponse->DeletionState->Status->StatusCode !== 0, 'message' => $shResponse->DeletionState->Status->StatusMessage);
	}
}
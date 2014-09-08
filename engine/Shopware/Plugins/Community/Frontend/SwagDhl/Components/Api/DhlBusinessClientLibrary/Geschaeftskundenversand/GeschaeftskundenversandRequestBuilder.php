<?php

require_once 'GeschaeftskundenversandWS/ISService_1_0_de.php';

class GeschaeftskundenversandRequestBuilder
{
	public function createDefaultShipmentDDRequest($shipper, $receiver, $orderInfo, $postNumber, $printOnlyIfCodeable)
	{
		$seqNumber = 1;
		$lblRespType = "URL";
		$shCompany = $this->createShipperCompany($shipper);
		$shAddress = $this->createShipperNativeAddressType($shipper);
		$shCommunication = $this->createShipperCommunicationType($shipper);
		$shVAT = null;
		if($shipper["taxNumber"]) {
			$shVAT = $shipper["taxNumber"];
		}
		$Shipper = new ShipperType($shCompany, $shAddress, $shCommunication, $shVAT);
		$ShipmentDetails = $this->createShipmentDetailsDDType($orderInfo, $shipper['bankAccount']);
		if($receiver['country'] == 'DE') {
			$ShipmentDetails->ProductCode = 'EPN';
		} else {
			$ShipmentDetails->ProductCode = 'BPI';
		}
		$rVAT = null;
		$Receiver = $this->createReceiver($receiver, $postNumber);
		$ExportDocument = NULL;
		$Identity = null;
		$FurtherAddresses = null;
		$Shipment = new Shipment($ShipmentDetails, $Shipper, $Receiver, $ExportDocument, $Identity, null, $FurtherAddresses);
		$Version = $this->createVersion();
		$shipmentOrderDDType = new ShipmentOrderDDType($seqNumber, $Shipment, null, $lblRespType, $printOnlyIfCodeable);
		$createShipmentDDRequest = new CreateShipmentDDRequest($Version, $shipmentOrderDDType);

		return $createShipmentDDRequest;
	}

	private function createReceiver($receiver, $postNumber)
	{
		$rCommunication = $this->createReceiverCommunicationType($receiver);

		if($receiver["street"] == "Packstation") {
			$packStation = new PackstationType($receiver["streetnumber"], $postNumber, $receiver["zipcode"], $receiver["city"]);
			$rCompany = $this->createReceiverCompany($receiver, null);
			$Receiver = new ReceiverType($rCompany, null, $packStation, null, $rCommunication, null);
		} elseif($receiver["street"] == "Postoffice") {
			$postOffice = new PostfilialeType($receiver["streetnumber"], $postNumber, $receiver["zipcode"], $receiver["city"]);
			$rCompany = $this->createReceiverCompany($receiver, null);
			$Receiver = new ReceiverType($rCompany, null, null, $postOffice, $rCommunication, null);
		} else {
			$rAddress = $this->createReceiverNativeAddressType($receiver);
			$rCompany = $this->createReceiverCompany($receiver, $postNumber);
			$Receiver = new ReceiverType($rCompany, $rAddress, null, null, $rCommunication, null);
		}

		return $Receiver;
	}

	private function createVersion()
	{
		$build = null;
		$version = new Version(1, 0, $build);
		return $version;
	}

	private function createDefaultShipmentItemDDType($weight)
	{
		$weight = str_replace(',', '.', $weight);
		$HeightInCM = null;
		$LengthInCM = null;
		$WeightInKG = $weight;
		$WidthInCM = null;
		$shipmentItem = new ShipmentItemType($WeightInKG, $LengthInCM, $WidthInCM, $HeightInCM);
		$shipmentItem->PackageType = "PK";
		return $shipmentItem;
	}

	private function createShipmentDetailsDDType($orderInfo, $bankData)
	{
		$today = new DateTime('NOW');
		$today->add(new DateInterval('P2D'));
		$Attendance = new Attendance($orderInfo['attendance']);
		$CustomerReference = null;
		$Description = null;
		$DeliveryRemarks = null;
		$insurance = null;
		$Service = $this->createService($orderInfo['useInsurance'], $orderInfo['isBulkfreight'], $orderInfo['useCod'], $orderInfo['codAmount']);
		$Notification = null;
		$BankData = null;
		if($orderInfo['useCod']) {
			$BankData = new BankType($bankData['accountOwner'], null, null, $bankData['bankName'], $bankData['accountIban'], $orderInfo['codNote'], $bankData['accountBic']);
		}
		$ShipmentItem = $this->createDefaultShipmentItemDDType($orderInfo['weight']);
		$NotificationEmailText = null;
		$shipmentDetails = new ShipmentDetailsDDType($orderInfo['ekp'], $Attendance, $CustomerReference, $Description, $DeliveryRemarks, $ShipmentItem, $Service, $Notification, $NotificationEmailText, $BankData);
		if($orderInfo['shippingDate']) {
			$shipmentDetails->ShipmentDate = $orderInfo['shippingDate'];
		} else {
			$shipmentDetails->ShipmentDate = $today->format('Y-m-d');
		}
		return $shipmentDetails;
	}

	private function createService($useInsurance, $isBulkfreight, $useCod, $codAmount)
	{
		$HigherInsurance = null;
		$Bulkfreight = null;
		$COD = null;
		$service = null;

		if($useInsurance) {
			$HigherInsurance = new HigherInsurance(2500, "EUR");
		}
		if($isBulkfreight) {
			$Bulkfreight = new Bulkfreight(null);
		}
		if($useCod) {
			$COD = new COD($codAmount, 'EUR');
		}
		if($HigherInsurance || $Bulkfreight || $COD) {
			$DDServiceGroupOtherType = new DDServiceGroupOtherType($HigherInsurance, $COD, NULL, NULL, $Bulkfreight, NULL, NULL);
			$service = new ShipmentServiceDD(null, null, null, null, null, $DDServiceGroupOtherType);
		}

		return $service;
	}

	private function createShipperCompany($shipper)
	{
		$company = new Company($shipper["company"], null);
		$name = new NameType(null, $company);
		return $name;
	}

	private function createShipperNativeAddressType($shipper)
	{
		$streetName = $shipper["shipperStreet"];
		$streetNumber = $shipper["shipperStreetNumber"];
		$city = $shipper["shipperCity"];
		$germany = null;
		$england = null;
		$other = $shipper["shipperZip"];
		$zip = new ZipType($germany, $england, $other);
		$country = null;
		$countryISOCode = "DE";
		$state = null;
		$Origin = new CountryType($country, $countryISOCode, $state);
		$floorNumber = null;
		$roomNumber = null;
		$languageCodeISO = null;
		$note = null;
		$careOfName = null;
		$district = null;
		$address = new NativeAddressType($streetName, $streetNumber, $careOfName, $zip, $city, $district, $Origin, $floorNumber, $roomNumber, $languageCodeISO, $note);
		return $address;
	}

	private function createShipperCommunicationType($shipper)
	{
		$phone = $shipper["shipperPhone"];
		$email = $shipper["email"];
		$fax = null;
		$mobile = null;
		$internet = null;
		$contactPerson = $shipper["shipperContactName"];
		$communication = new CommunicationType($phone, $email, $fax, $mobile, $internet, $contactPerson);

		return $communication;
	}

	private function createReceiverCompany($receiver, $postNumber)
	{
		$Person = null;
		$Company = null;
		if($receiver['company']) {
			$name1 = $receiver['company'];
			$name2 = $postNumber;
			$Company = new Company($name1, $name2);
		} else {
			$name1 = $receiver['firstname'] . ' ' . $receiver['lastname'];
			$name2 = $postNumber;
			$Company = new Company($name1, $name2);
		}
		$name = new NameType($Person, $Company);
		return $name;
	}

	private function createReceiverNativeAddressType($receiver)
	{
		$streetName = null;
		$streetNumber = null;
		$city = null;
		$germany = null;
		$england = null;
		$other = null;
		$countryISOCode = null;
		$country = null;
		$state = null;
		$careOfName = null;
		$district = null;
		$floorNumber = null;
		$roomNumber = null;
		$languageCodeISO = null;
		$note = null;

		$streetName = $receiver['street'];
		$streetNumber = $receiver['streetnumber'];
		$city = $receiver['city'];
		$other = $receiver['zipcode'];
		$countryISOCode = $receiver['country'];

		$Zip = new ZipType($germany, $england, $other);
		$Origin = new CountryType($country, $countryISOCode, $state);
		$address = new NativeAddressType($streetName, $streetNumber, $careOfName, $Zip, $city, $district, $Origin, $floorNumber, $roomNumber, $languageCodeISO, $note);

		return $address;
	}

	private function createReceiverCommunicationType($receiver)
	{
		$phone = $receiver['phone'];
		$email = $receiver['email'];
		$fax = null;
		$mobile = null;
		$internet = null;
		if($receiver['company'] || $receiver['country'] != 'DE') {
			$contactPerson = $receiver['firstname'] . ' ' . $receiver['lastname'];
		} else {
			$contactPerson = null;
		}
		$communication = new CommunicationType($phone, $email, $fax, $mobile, $internet, $contactPerson);

		return $communication;
	}

	public function getDefaultLabelDDRequest($shipmentId)
	{
		$ddRequest = new GetLabelDDRequest(NULL, NULL);
		$ddRequest->Version = $this->createVersion();
		$shNumber = new ShipmentNumberType(NULL, NULL, NULL, NULL);
		if($shipmentId != '') {
			$shNumber->shipmentNumber = $shipmentId;
		} else {
			$shNumber->shipmentNumber = 00000000;
		}
		$ddRequest->ShipmentNumber = $shNumber;
		return $ddRequest;
	}

	public function getDeleteShipmentDDRequest($shipmentId)
	{
		$ddRequest = new DeleteShipmentDDRequest(NULL, NULL);
		$ddRequest->Version = $this->createVersion();
		$shNumber = new ShipmentNumberType(NULL, NULL, NULL, NULL);
		if($shipmentId != '') {
			$shNumber->shipmentNumber = $shipmentId;
		} else {
			$shNumber->shipmentNumber = 00000000;
		}
		$ddRequest->ShipmentNumber = $shNumber;
		return $ddRequest;
	}
}
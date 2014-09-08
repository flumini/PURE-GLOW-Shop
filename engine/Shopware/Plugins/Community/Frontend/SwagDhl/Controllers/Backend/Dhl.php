<?php

use Shopware\SwagDhl\Structs\Address;
use Shopware\SwagDhl\Structs\OrderInfo;

/**
 * Class Shopware_Controllers_Backend_Dhl
 * Backend Controller
 */
Class Shopware_Controllers_Backend_Dhl extends Shopware_Controllers_Backend_ExtJs
{
	public function getOrdersAction()
	{
		$builder = Shopware()->Models()->createQueryBuilder();

		$builder->select('orders', 'attribute', 'customer', 'billing', 'shipping', 'shippingAttribute')
				->from('Shopware\Models\Order\Order', 'orders')
				->leftJoin('orders.attribute', 'attribute')
				->leftJoin('orders.customer', 'customer')
				->innerJoin('customer.billing', 'billing')
				->leftJoin('orders.shipping', 'shipping')
				->leftJoin('shipping.attribute', 'shippingAttribute')
				->innerJoin('orders.dispatch', 'dispatch')
				->leftJoin('dispatch.attribute', 'dispatchAttribute')
				->where('dispatchAttribute.swagDhlDispatch = 1')
				->andWhere('orders.number != 0');

		//If a filter is set
		if($this->Request()->getParam('filter')) {
			//Get the value itself
			$filters = $this->Request()->getParam('filter');
			foreach($filters as $filter) {
				$builder->andWhere($builder->expr()->orX(
						'orders.number LIKE :value',
						'billing.firstName LIKE :value',
						'billing.lastName LIKE :value'))
						->setParameter('value', "%" . $filter["value"] . "%");
			}
		}

		$sort = $this->Request()->getParam('sort');
		if($sort) {
			$sorting = $sort[0];
			switch($sorting['property']) {
				case 'orderTime':
					$builder->orderBy('orders.orderTime', $sorting['direction']);
					break;
				case 'number':
					$builder->orderBy('orders.number', $sorting['direction']);
					break;
				case 'fullName':
					$builder->orderBy('billing.lastName', $sorting['direction']);
					break;
				case 'invoiceAmount':
					$builder->orderBy('orders.invoiceAmount', $sorting['direction']);
					break;
				case 'dhlShippingNumber':
					$builder->orderBy('orders.trackingCode', $sorting['direction']);
					break;
				default:
					$builder->orderBy('orders.orderTime', 'DESC');
			}
		} else {
			$builder->orderBy('orders.orderTime', 'DESC');
		}

		$builder->setFirstResult($this->Request()->getParam('start'))->setMaxResults($this->Request()->getParam('limit'));

		$query = $builder->getQuery();

		$totalCount = Shopware()->Models()->getQueryCount($builder->getQuery());
		$result = $query->getArrayResult();

		$usedPayment = Shopware()->Plugins()->Frontend()->SwagDhl()->Config()->get('codPayment');

		$data = array();
		foreach($result as $key => $order) {
			$address = unserialize($order['attribute']['swagDhlAddress']);
			$orderInfo = unserialize($order['attribute']['swagDhlOrderInfo']);

			$useCod = ($order['paymentId'] == $usedPayment);

			$data[$key]['id'] = $order["id"];
			$data[$key]['orderTime'] = $order["orderTime"];
			$data[$key]['number'] = $order["number"];
			$data[$key]['fullName'] = $address->firstName . " " . $address->lastName;
			$data[$key]['firstName'] = $address->firstName;
			$data[$key]['lastName'] = $address->lastName;
			$data[$key]['invoiceAmount'] = $order['invoiceAmount'];
			$data[$key]['transactionId'] = $order["transactionId"];
			$data[$key]['dhlLabel'] = $orderInfo->labelUrl;
			$data[$key]['dhlShippingNumber'] = $order["trackingCode"];
			$data[$key]["statusShipped"] = !empty($orderInfo->labelUrl) ? 1 : 0;
			$data[$key]["statusManifested"] = !empty($orderInfo->labelUrl) ? $this->getManifestingStatus($orderInfo->labelTime) : 0;
			$data[$key]["street"] = $address->street;
			$data[$key]["streetNumber"] = $address->streetNumber;
			$data[$key]["city"] = $address->city;
			$data[$key]["zip"] = $address->zip;
			$data[$key]["weight"] = $orderInfo->weight;
			$data[$key]["postNumber"] = $address->postNumber;
			$data[$key]["attendance"] = $orderInfo->attendance;
			$data[$key]['useInsurance'] = $orderInfo->useInsurance;
			$data[$key]['isBulkfreight'] = $orderInfo->isBulkfreight;
			$data[$key]['useCod'] = $useCod;
			$data[$key]['country'] = $address->country;
		}

		$this->View()->assign(array('data' => $data, 'success' => true, 'total' => $totalCount));
	}

	public function getAttendancesAction()
	{
		$country = $this->Request()->getParam('country');
		if($country == "Deutschland") {
			$attendances = str_replace(' ', '', Shopware()->Plugins()->Frontend()->SwagDhl()->Config()->get('subShippingTypeEPN'));
		} else {
			$attendances = str_replace(' ', '', Shopware()->Plugins()->Frontend()->SwagDhl()->Config()->get('subShippingTypeBPI'));
		}
		$attendances = explode(';', $attendances);

		foreach($attendances as $key => $attendance) {
			$attendances[$key] = array('value' => $attendance);
		}

		$this->View()->assign(array('success' => true, 'data' => $attendances));
	}

	private function getManifestingStatus(DateTime $date)
	{
		$date = new DateTime($date);

		//Gives back the date of yesterday at 0 pm so we add 18 hours with each 3600 seconds to be at 18:00:00
		$dateStringYesterday = strtotime("yesterday") + 18 * 3600;

		//Same for today
		$maxDateStringToday = strtotime("today") + 18 * 3600;

		if($date->getTimestamp() > $dateStringYesterday && $date->getTimestamp() < $maxDateStringToday) {
			return 0;
		} else {
			return 1;
		}
	}

	public function printLabelsAction()
	{
		$orderNumbers = explode(",", $this->Request()->getParam('selections'));
		$first = (bool) $this->Request()->getParam('first');

		include_once 'engine/Library/Fpdf/fpdf.php';
		include_once 'engine/Library/Fpdf/fpdi.php';

		$pdf = new FPDI();

		$docPath = Shopware()->DocPath('files/documents') . "dhl/";

		$files = array();
		foreach($orderNumbers as $orderNumber) {

			if(!$orderNumber) {
				continue;
			}
			/* @var \Shopware\Models\Order\Order $orderModel */
			$orderModel = Shopware()->Models()->getRepository('Shopware\Models\Order\Order')->findOneBy(array('number' => $orderNumber));
			if(!$orderModel) {
				continue;
			}
			$attributeModel = $orderModel->getAttribute();

			$string = '';
			if($attributeModel) {
				$orderInfo = unserialize($attributeModel->getSwagDhlOrderInfo());
				$string = $orderInfo->labelUrl;

				if(!$string) {
					$this->View()->assign(array('success' => false, 'message' => 'Label für Bestellung ' . $orderNumber . ' nicht vorhanden!'));
					return;
				} elseif($string && $first) {
					$this->View()->assign(array('success' => true));
					return;
				}
			}

			$pathHash = md5(uniqid(rand()));
			$filePath = $docPath . $pathHash . '.pdf';

			mkdir($docPath, 0700);

			file_put_contents($filePath, file_get_contents(trim($string)));
			$files[] = $filePath;
		}

		foreach($files as $path) {

			$numPages = $pdf->setSourceFile($path);
			for($i = 1; $i <= $numPages; $i++) {
				$template = $pdf->ImportPage($i);
				$size = $pdf->getTemplatesize($template);
				$pdf->AddPage('P', array($size['w'], $size['h']));
				$pdf->useTemplate($template);
			}
			unlink($path);
		}

		$hash = md5(uniqid(rand()));
		$pdf->Output($hash . '.pdf', "D");

		Shopware()->Plugins()->Controller()->ViewRenderer()->setNoRender();
		$this->Front()->Plugins()->Json()->setRenderer(false);
	}

	public function sendOrderAction()
	{
		$this->saveShippingDetailsAction();

		$config = Shopware()->Plugins()->Frontend()->SwagDhl()->Config();
		$shipper = $this->getShipperData($config);

		$number = $this->Request()->getParam('orderNumber');
		/* @var \Shopware\Models\Order\Order $orderModel */
		$orderModel = Shopware()->Models()->getRepository('Shopware\Models\Order\Order')->findOneBy(array('number' => $number));
		$params = $this->Request()->getParams();
		$receiver = $this->getReceiverData($orderModel, $params);

		$intraAuthentication = array('user' => $config->intraShipUser, 'password' => $config->intraShipPassword);

		$attendance = $this->Request()->getParam('attendance');

		$ekp = $config->ekp;

		$useInsurance = $this->Request()->getParam('useInsurance');

		$isBulkfreight = $this->Request()->getParam('isBulkfreight');

		if($config->weightFlat) {
			$weight = 1;
		} else {
			$weight = $this->Request()->getParam('weight');
			$weight = str_replace(',', '.', $weight);
		}

		$date = $this->Request()->getParam('shippingDate');
		$dateTemp = new DateTime($date);
		$shippingDate = date('Y-m-d', $dateTemp->getTimestamp());

		$postNumber = $this->Request()->getParam('postNumber');

		$printOnlyIfCodeable = $this->Request()->getParam('printOnlyIfCodeable');

		$useCod = $this->Request()->getParam('useCod');
		$codAmount = $this->Request()->getParam('invoiceAmount');
		$codNote = $this->Request()->getParam('reasonForPayment');

		$orderInfo = array(
			'attendance' => $attendance,
			'ekp' => $ekp,
			'useInsurance' => $useInsurance,
			'isBulkfreight' => $isBulkfreight,
			'weight' => $weight,
			'shippingDate' => $shippingDate,
			'useCod' => $useCod,
			'codAmount' => $codAmount,
			'codNote' => $codNote);

		$data = Shopware()->SwagDhlFactory()->getVersandClient()->createShipment($shipper, $receiver, $intraAuthentication, $orderInfo, $postNumber, $printOnlyIfCodeable);

		if(!$data["error"]) {
			if($data["warning"]) {
				$this->View()->assign(array('success' => false, 'message' => $data["message"]));
			} else {
				$label = $data["label"];
				$trackingCode = $data["shipmentNumber"];
				$attributeModel = $orderModel->getAttribute();
				$orderInfo = unserialize($attributeModel->getSwagDhlOrderInfo());
				if(!is_object($orderInfo)) {
					$orderInfo = new OrderInfo();
				}
				$orderInfo->labelUrl = $label;
				$orderInfo->labelTime = date('Y-m-d H:i:s');
				$orderInfo = serialize($orderInfo);
				$attributeModel->setSwagDhlOrderInfo($orderInfo);
				$orderModel->setTrackingCode($trackingCode);
				Shopware()->Models()->persist($attributeModel);
				Shopware()->Models()->flush();

				$this->View()->assign(array('success' => true, 'message' => $data["message"]));
			}
		} else {
			$this->View()->assign(array('success' => false, 'message' => $data["message"]));
		}
	}

	private function getShipperData($config)
	{
		$shipperDetails = Shopware()->Config();
		$shipper = array(
			'shipperContactName' => $config->get('shipperContactName'),
			'shipperStreet' => $config->get('shipperStreet'),
			'shipperStreetNumber' => $config->get('shipperStreetNumber'),
			'shipperCity' => $config->get('shipperCity'),
			'shipperZip' => $config->get('shipperZip'),
			'shipperPhone' => $config->get('shipperPhone'),
			'shopName' => $shipperDetails->shopName,
			'bankAccount' => array(
				'accountOwner' => $config->get('shipperAccountOwner'),
				'accountIban' => $config->get('shipperAccountIban'),
				'accountBic' => $config->get('shipperAccountBic'),
				'bankName' => $config->get('shipperBankName')),
			'email' => $config->get('shipperEmail'),
			'company' => $config->get('shipperCompany'));

		return $shipper;
	}

	private function getReceiverData(\Shopware\Models\Order\Order $orderModel, $params)
	{
		/* @var \Shopware\Models\Customer\Customer $customerModel */
		$customerModel = $orderModel->getCustomer();
		$orderBilling = $orderModel->getBilling();
		$orderShipping = $orderModel->getShipping();

		$receiver = array();
		$receiver["firstname"] = $params['firstName'];
		$receiver["lastname"] = $params['lastName'];
		$receiver["street"] = $params['street'];
		$receiver["streetnumber"] = $params['streetNumber'];
		$receiver["zipcode"] = $params['zip'];
		$receiver["city"] = $params['city'];
		$receiver['email'] = $customerModel->getEmail();
		$receiver['phone'] = $orderBilling->getPhone();
		$receiver['country'] = Shopware()->Models()->find('Shopware\Models\Country\Country', $orderShipping->getCountry())->getIso();
		$receiver['company'] = $orderShipping->getCompany();

		return $receiver;
	}

	public function saveShippingDetailsAction()
	{
		$firstName = $this->Request()->getParam('firstName');
		$lastName = $this->Request()->getParam('lastName');
		$street = $this->Request()->getParam('street');
		$streetNumber = $this->Request()->getParam('streetNumber');
		$city = $this->Request()->getParam('city');
		$zip = $this->Request()->getParam('zip');
		$postNumber = $this->Request()->getParam('postNumber');
		$number = $this->Request()->getParam('orderNumber');
		$weight = $this->Request()->getParam('weight');
		$attendance = $this->Request()->getParam('attendance');
		$useInsurance = $this->Request()->getParam('useInsurance');
		$isBulkfreight = $this->Request()->getParam('isBulkfreight');

		/* @var \Shopware\Models\Order\Order $orderModel */
		$orderModel = Shopware()->Models()->getRepository('Shopware\Models\Order\Order')->findOneBy(array('number' => $number));
		$attribute = $orderModel->getAttribute();

		$address = unserialize($attribute->getSwagDhlAddress());
		if(!is_object($address)) {
			$address = new Address();
		}
		$address->streetNumber = $streetNumber;
		$address->street = $street;
		$address->city = $city;
		$address->zip = $zip;
		$address->firstName = $firstName;
		$address->lastName = $lastName;
		$address->postNumber = $postNumber;
		$address = serialize($address);

		$orderInfo = unserialize($attribute->getSwagDhlOrderInfo());
		if(!is_object($orderInfo)) {
			$orderInfo = new OrderInfo();
		}
		$orderInfo->weight = $weight;
		$orderInfo->attendance = $attendance;
		$orderInfo->useInsurance = $useInsurance;
		$orderInfo->isBulkfreight = $isBulkfreight;
		$orderInfo = serialize($orderInfo);

		$attribute->setSwagDhlAddress($address);
		$attribute->setSwagDhlOrderInfo($orderInfo);

		Shopware()->Models()->persist($attribute);
		Shopware()->Models()->flush();

		$this->View()->assign(array('success' => true));
	}

	public function cancelOrderAction()
	{
		$shippingNumber = $this->Request()->getParam("shippingNumber");
		$config = Shopware()->Plugins()->Frontend()->SwagDhl()->Config();
		/* @var \Shopware\Models\Order\Order $orderModel */
		$orderModel = Shopware()->Models()->getRepository('Shopware\Models\Order\Order')->findOneBy(array('trackingCode' => $shippingNumber));
		$attributeModel = $orderModel->getAttribute();

		$intraAuthentication = array('user' => $config->intraShipUser, 'password' => $config->intraShipPassword);

		$data = Shopware()->SwagDhlFactory()->getVersandClient()->deleteShipment($intraAuthentication, $shippingNumber);

		if($data["error"]) {
			$this->View()->assign(array('success' => false, 'message' => $data["message"]));
		} else {
			$orderInfo = unserialize($attributeModel->getSwagDhlOrderInfo());
			if(!is_object($orderInfo)) {
				$orderInfo = new OrderInfo();
			}
			$orderInfo->labelUrl = null;
			$orderInfo->labelTime = null;
			$orderInfo = serialize($orderInfo);
			$attributeModel->setSwagDhlOrderInfo($orderInfo);
			$orderModel->setTrackingCode(NULL);

			Shopware()->Models()->persist($attributeModel);
			Shopware()->Models()->flush();

			$this->View()->assign(array('success' => true));
		}
	}
}

<?php

/**
 * FrontendDHL controller class
 *
 * todo@all: Documentation
 */
class Shopware_Controllers_Frontend_Dhl extends Enlight_Controller_Action
{
	/**
	 * @var $admin
	 */
	protected $admin;

	/**
	 * Init controller method
	 */

	public function init()
	{
		$this->admin = Shopware()->Modules()->Admin();
	}

	/**
	 * Find Packstation Action method
	 *
	 * Loads the iFrame for modal window
	 */

	public function findPackstationAction()
	{
		$target = $this->Request()->getParam('sTarget', $this->Request()->getControllerName());
		$userData = Shopware()->Modules()->Admin()->sGetUserData();

		$city = $userData["shippingaddress"]["city"];
		$zip = $userData["shippingaddress"]["zipcode"];
		$this->View()->loadTemplate('frontend/swag_dhl/account/find_packstation.tpl');
		$this->View()->sTarget = $target;
		$this->View()->city = $city;
		$this->View()->zip = $zip;
	}

	/**
	 * Get User Input Action
	 *
	 * gets the PLZ and city and returns the packstations array
	 * or error messages
	 *
	 * all the values from plugin configuration is set
	 *
	 * makes a SOAP request to DHL to get the Packstations
	 */

	public function getPackstationsAction()
	{
		$config = Shopware()->Plugins()->Frontend()->SwagDhl()->Config();
		$target = $this->Request()->getParam('sTarget', $this->Request()->getControllerName());
		$searchCity = $this->Request()->getParam('city');
		$searchPlz = $this->Request()->getParam('plz');
		$packstations = Shopware()->SwagDhlFactory()->getSoapClient()->getPackstations($searchCity, $searchPlz);

		$this->View()->loadTemplate('frontend/swag_dhl/account/load_packstations.tpl');
		if(is_array($packstations)) {
			$this->View()->packstations = $packstations;
		} else {
			$this->View()->errorMessage = $packstations;
		}
		$this->View()->packstations = $packstations;

		$this->View()->mapKey = $config['googleMapsKey'];
		$this->View()->sTarget = $target;
		$this->View()->zip = $searchPlz;
		$this->View()->city = $searchCity;
	}

	/**
	 * Find Postoffice Action method
	 *
	 * loads the iframe for the postoffice google map
	 *
	 */
	public function findPostofficeAction()
	{
		$target = $this->Request()->getParam('sTarget', $this->Request()->getControllerName());
		$userData = Shopware()->Modules()->Admin()->sGetUserData();

		$city = $userData["shippingaddress"]["city"];
		$zip = $userData["shippingaddress"]["zipcode"];
		$this->View()->loadTemplate('frontend/swag_dhl/account/find_postoffice.tpl');
		$this->View()->sTarget = $target;
		$this->View()->city = $city;
		$this->View()->zip = $zip;
	}

	/**
	 * Get Postoffices Action method
	 *
	 * All the values from plugin configuration is set
	 *
	 * makes a SOAP request to DHL to get the Postfiliale
	 */
	public function getPostofficesAction()
	{
		$config = Shopware()->Plugins()->Frontend()->SwagDhl()->Config();
		$target = $this->Request()->getParam('sTarget', $this->Request()->getControllerName());
		$searchCity = $this->Request()->getParam('city');
		$searchPlz = $this->Request()->getParam('plz');

		$postOffices = Shopware()->SwagDhlFactory()->getSoapClient()->getPostoffices($searchCity, $searchPlz);
		$this->View()->loadTemplate('frontend/swag_dhl/account/load_postoffices.tpl');
		if(is_object($postOffices)) {
			$newPostOffices = array();
			$newPostOffices[0] = $postOffices;
		} else {
			$newPostOffices = $postOffices;
		}

		if(is_array($newPostOffices)) {
			//Only show the ones with a depot-service-number
			foreach($newPostOffices as $key => $postOffice) {
				if(!$postOffice->depotServiceNo) {
					unset($newPostOffices[$key]);
				}
			}
			$newPostOffices = array_values($newPostOffices);

			$this->View()->postOffices = $newPostOffices;
		} else {
			$this->View()->errorMessage = $newPostOffices;
		}

		$this->View()->mapKey = $config['googleMapsKey'];
		$this->View()->sTarget = $target;
		$this->View()->zip = $searchPlz;
		$this->View()->city = $searchCity;
	}

	/**
	 * Select Shipping Action method
	 *
	 * gives in the default values for the forms
	 */
	public function selectShippingAction()
	{
		$packstationAddress = $this->Request()->getPost();
		$streetName = $this->Request()->getParam('streetName', 'not set');
		$pa = $this->Request()->getParam('pa', false);
		$pb = $this->Request()->getParam('pb', false);
		$PackstationNumber = $packstationAddress['psId'];
		$PackstationStreet = $streetName;
		$PackstationStreetNumber = $packstationAddress['streetNumber'];
		$PackstationZip = $packstationAddress['zip'];
		$PackstationCity = $packstationAddress['city'];
		$target = $this->Request()->getParam('sTarget', $this->Request()->getControllerName());
		$address = $this->View()->sUserData['shippingaddress'];
		$this->View()->sFormData = $address;
		$this->View()->sTarget = $target;
		$this->View()->sShippingPreviously = $this->admin->sGetPreviousAddresses('shipping');
		$this->View()->sCountryList = $this->admin->sGetCountryList();
		$this->View()->sUserData = $this->admin->sGetUserData();
		$this->View()->PackstationNumber = $PackstationNumber;
		$this->View()->PackstationStreet = $PackstationStreet;
		$this->View()->PackstationStreetNumber = $PackstationStreetNumber;
		$this->View()->PackstationZip = $PackstationZip;
		$this->View()->PackstationCity = $PackstationCity;
		$this->View()->pa = $pa;
		$this->View()->pb = $pb;
	}

	/**
	 * Save Shipping Action Method
	 *
	 * Saves the new shipping address values to the account controller
	 * updates the shipping address
	 *
	 */
	public function saveShippingAction()
	{
		$params = $this->Request()->getPost();

		if($this->Request()->isPost()) {
			$identifier = $params["identifier"];
			$userId = $params["userId"];

			$shippingPersister = new \Shopware\SwagDhl\Components\ShippingPersister(Shopware()->Models());
			$shippingPersister->persist($userId, $identifier, $params["postnumber"], $params["street"], $params["streetnumber"], $params["city"], $params["plz"], $params["number"]);
		}

		$target = 'checkout';
		$this->redirect(array('controller' => $target, 'action' => 'index', 'success' => 'shipping'));
	}

	public function selectShippingDispatchAction()
	{
		Shopware()->Session()->dhlDispatch = $this->Request()->getParam('identifier');

		$this->View()->loadTemplate('');
		echo $this->Request()->getParam('identifier');
	}
}
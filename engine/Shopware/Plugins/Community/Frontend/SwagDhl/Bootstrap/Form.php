<?php

namespace Shopware\SwagDhl\Bootstrap;

class Form
{
	public function create(\Shopware\Models\Config\Form $form)
	{
		//getting payment methods for COD config
		/* @var \Shopware\Models\Payment\Repository $repository*/
		$repository = Shopware()->Models()->getRepository('Shopware\Models\Payment\Payment');
		$query = $repository->getPaymentsQuery();
		$data = $query->getArrayResult();
		$payments = array();
		foreach($data as $payment) {
			$payments[] = array($payment['id'], $payment['description']);
		}

		$form->setElement('button', 'openForm', array('label' => 'Neukunde?', 'handler' => 'function () { window.open("http://www.shopware.de/dhl/"); }'));

		$form->setElement('text', 'intraShipUser', array('required' => 1, 'label' => 'DHL Intraship-Benutzer'));
		$form->setElement('text', 'intraShipPassword', array('required' => 1, 'label' => 'DHL Intraship-Passwort'));
		$form->setElement('text', 'ekp', array('required' => 1, 'label' => 'DHL EKP-Nummer'));

		$form->setElement('text', 'subShippingTypeEPN', array('required' => 1, 'label' => 'DHL Teilnahme National',
			'description' => 'Sie können mehrere Teilnahmen anhand eines Semikolons auflisten. Beispiel: 01;AB;C2'));
		$form->setElement('text', 'subShippingTypeBPI', array('required' => 1, 'label' => 'DHL Teilnahme International',
			'description' => 'Sie können mehrere Teilnahmen anhand eines Semikolons auflisten. Beispiel: 01;AB;C2'));

		$form->setElement('boolean', 'weightFlat', array('label' => 'Gewichtsflat', 'value' => 0));

		$form->setElement('text', 'shipperContactName', array('required' => 1, 'label' => 'Absender Kontaktname (Vor- / Nachname)'));
		$form->setElement('text', 'shipperStreet', array('required' => 1, 'label' => 'Absender Straße'));
		$form->setElement('text', 'shipperStreetNumber', array('required' => 1, 'label' => 'Absender Straßennummer'));
		$form->setElement('text', 'shipperZip', array('required' => 1, 'label' => 'Absender Postleitzahl'));
		$form->setElement('text', 'shipperCity', array('required' => 1, 'label' => 'Absender Stadt'));
		$form->setElement('text', 'shipperPhone', array('required' => 1, 'label' => 'Absender Telefonnummer'));
		$form->setElement('text', 'shipperCompany', array('required' => 1, 'label' => 'Absender Firma'));
		$form->setElement('text', 'shipperEmail', array('required' => 1, 'label' => 'Absender E-Mail'));

		$form->setElement('select', 'codPayment', array('label' => 'Zahlungsart Nachnahme', 'store' => $payments, 'value' => 3,
			'description' => 'Hier wird die Zahlungsart ausgewählt, bei der mit DHL per Nachnahme verschickt wird. Standard ist die Shopware Standard Nachnahme Zahlungsart'));
		$form->setElement('text', 'shipperAccountOwner', array('label' => 'Kontoinhaber'));
		$form->setElement('text', 'shipperAccountIban', array('label' => 'IBAN'));
		$form->setElement('text', 'shipperAccountBic', array('label' => 'BIC'));
		$form->setElement('text', 'shipperBankName', array('label' => 'Bankname'));

		$form->setElement('boolean', 'showPostOffice', array('label' => 'Postfilialen-Suche anzeigen', 'value' => 1, 'required' => 1));
		$form->setElement('boolean', 'showPackStation', array('label' => 'Packstationen-Suche anzeigen', 'value' => 1, 'required' => 1));

		$form->setElement('text', 'googleMapsKey', array('label' => 'Google Maps Key'));

		$shopRepository = Shopware()->Models()->getRepository('\Shopware\Models\Shop\Locale');
		$translations = array(
			'en_GB' => array(
				'openForm' => array('label' => 'new customer?'),
				'intraShipUser' => array('label' => 'DHL intraship user'),
				'intraShipPassword' => array('label' => 'DHL intraship password'),
				'ekp' => array('label' => 'DHL EKP number'),
				'subShippingTypeEPN' => array('label' => 'DHL attendance national', 'description' => 'you can list multiple attendances, separated by semicolon. e.g. 01;AB;C2'),
				'subShippingTypeBPI' => array('label' => 'DHL attendance international', 'description' => 'you can list multiple attendances, separated by semicolon. e.g. 01;AB;C2'),
				'weightFlat' => array('label' => 'weight flatrate'),
				'shipperContactName' => array('label' => 'shipper contact name (first / last name)'),
				'shipperStreet' => array('label' => 'shipper street'),
				'shipperStreetNumber' => array('label' => 'shipper street number'),
				'shipperZip' => array('label' => 'shipper zip code'),
				'shipperCity' => array('label' => 'shipper city'),
				'shipperPhone' => array('label' => 'shipper phone number'),
				'shipperCompany' => array('label' => 'shipper company'),
				'shipperEmail' => array('label' => 'shipper e-mail'),
				'codPayment' => array('label' => 'payment method for COD', 'description' => 'here you select the payment method for which DHL uses COD for shipping'),
				'shipperAccountOwner' => array('label' => 'account owner'),
				'shipperAccountIban' => array('label' => 'IBAN'),
				'shipperAccountBic' => array('label' => 'BIC'),
				'shipperBankName' => array('label' => 'bank name'),
				'showPostOffice' => array('label' => 'show post office search'),
				'showPackStation' => array('label' => 'show packstation search')
			)
		);

		foreach($translations as $locale => $snippets) {
			$localeModel = $shopRepository->findOneBy(array(
				'locale' => $locale
			));

			if($localeModel === null){
				continue;
			}

			foreach($snippets as $element => $snippet) {
				$elementModel = $form->getElement($element);
				if($elementModel === null) {
					continue;
				}
				$translationModel = new \Shopware\Models\Config\ElementTranslation();
				$translationModel->setLabel($snippet['label']);
				$translationModel->setDescription($snippet['description']);
				$translationModel->setLocale($localeModel);
				$elementModel->addTranslation($translationModel);
			}
		}
	}
}
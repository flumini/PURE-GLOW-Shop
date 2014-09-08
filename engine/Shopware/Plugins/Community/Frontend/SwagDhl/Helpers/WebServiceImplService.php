<?php
namespace Shopware\SwagDhl\Helpers;

class WebServiceImplService extends \SoapClient
{
	/**
	 *
	 * @param array $options
	 * @param string $wsdl The wsdl file to use
	 * @internal param array $config A array of config values
	 * @access public
	 */
	public function __construct(array $options = array(), $wsdl = 'http://post.doubleslash.de/webservice/?wsdl')
	{
		parent::__construct($wsdl, $options);
	}

	/**
	 *
	 * @param getPackstationsByAddress $parameters
	 * @return mixed
	 * @access public
	 */
	public function getPackstationsByAddress(getPackstationsByAddress $parameters)
	{
		return $this->__soapCall('getPackstationsByAddress', array($parameters));
	}

	/**
	 *
	 * @param getBranchesByAddress $parameters
	 * @return mixed
	 * @access public
	 */
	public function getBranchesByAddress(getBranchesByAddress $parameters)
	{
		return $this->__soapCall('getBranchesByAddress', array($parameters));
	}
}

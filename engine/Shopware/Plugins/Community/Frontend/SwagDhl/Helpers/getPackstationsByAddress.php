<?php
namespace Shopware\SwagDhl\Helpers;
class getPackstationsByAddress
{
	/**
	 *
	 * @var string $key
	 * @access public
	 */
	public $key;

	/**
	 *
	 * @var inputAddress $address
	 * @access public
	 */
	public $address;

	/**
	 *
	 * @param string $key
	 * @param inputAddress $address
	 * @access public
	 */
	public function __construct($key, $address)
	{
		$this->key = $key;
		$this->address = $address;
	}
}
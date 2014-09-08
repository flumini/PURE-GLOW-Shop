<?php
namespace Shopware\SwagDhl\Helpers;
class inputAddress
{
	/**
	 *
	 * @var string $street
	 * @access public
	 */
	public $street;

	/**
	 *
	 * @var string $streetNo
	 * @access public
	 */
	public $streetNo;

	/**
	 *
	 * @var string $zip
	 * @access public
	 */
	public $zip;

	/**
	 *
	 * @var string $city
	 * @access public
	 */
	public $city;

	/**
	 *
	 * @param string $street
	 * @param string $streetNo
	 * @param string $zip
	 * @param string $city
	 * @access public
	 */
	public function __construct($street, $streetNo, $zip, $city)
	{
		$this->street = $street;
		$this->streetNo = $streetNo;
		$this->zip = $zip;
		$this->city = $city;
	}
}
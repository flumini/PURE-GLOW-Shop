<?php
namespace Shopware\SwagDhl\Helpers;

class getBranchesByAddress
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
	 * @var string $service
	 * @access public
	 */
	public $service;

	/**
	 *
	 * @var inputTimeinfo $timeinfo
	 * @access public
	 */
	public $timeinfo;

	/**
	 *
	 * @var boolean $hasPackageAcceptance
	 * @access public
	 */
	public $hasPackageAcceptance;

	/**
	 *
	 * @var boolean $hasNoPackageAcceptance
	 * @access public
	 */
	public $hasNoPackageAcceptance;

	/**
	 *
	 * @param string $key
	 * @param inputAddress $address
	 * @param string $service
	 * @param inputTimeinfo $timeinfo
	 * @param boolean $hasPackageAcceptance
	 * @param boolean $hasNoPackageAcceptance
	 * @access public
	 */
	public function __construct($key, $address, $service, $timeinfo, $hasPackageAcceptance, $hasNoPackageAcceptance)
	{
		$this->key = $key;
		$this->address = $address;
		$this->service = $service;
		$this->timeinfo = $timeinfo;
		$this->hasPackageAcceptance = $hasPackageAcceptance;
		$this->hasNoPackageAcceptance = $hasNoPackageAcceptance;
	}
}
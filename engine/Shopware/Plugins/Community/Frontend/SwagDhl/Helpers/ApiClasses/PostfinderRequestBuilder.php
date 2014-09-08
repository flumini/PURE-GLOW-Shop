<?php

namespace Shopware\SwagDhl\Helpers\ApiClasses;

use Shopware\SwagDhl\Helpers\getBranchesByAddress;
use Shopware\SwagDhl\Helpers\getPackstationsByAddress;
use Shopware\SwagDhl\Helpers\inputTimeinfo;

class PostfinderRequestBuilder
{
	private $conf_file_path = "test-konfiguration.ini";
	public $is_open_today = "offenHeute";
	public $weekday = "wochentag";
	public $time = "zeit";
	public $has_pkg_accept = "paketannahme";
	public $has_no_pkg_accept = "ohnePaketannahme";
	public $defaultProps = null;
	public $defaultBranchService = "0";

	public function loadProperties()
	{
		try {
			$this->defaultProps = parse_ini_file($this->conf_file_path);
		}
		catch(\Exception $e) {
			echo $e;
		}
	}

	public function createDefaultPkgAccept()
	{
		if($this->defaultProps == null) {
			$this->loadProperties();
		}
		return filter_var($this->defaultProps[$this->has_pkg_accept], FILTER_VALIDATE_BOOLEAN);
	}

	public function createDefaultNoPkgAccept()
	{
		if($this->defaultProps == null) {
			$this->loadProperties();
		}
		return filter_var($this->defaultProps[$this->has_no_pkg_accept], FILTER_VALIDATE_BOOLEAN);
	}

	public function createDefaultTimeInfo()
	{
		if($this->defaultProps == null) {
			$this->loadProperties();
		}
		$isOpenedToday = filter_var($this->defaultProps[$this->is_open_today], FILTER_VALIDATE_BOOLEAN);
		$weekday = $this->defaultProps[$this->weekday];
		$time = $this->defaultProps[$this->time];
		$timeInf = new inputTimeinfo($isOpenedToday, $weekday, $time);
		return $timeInf;
	}

	public function getBranchesByAddr($key, $inputAddr)
	{
		$getBranchesByAddr = new getBranchesByAddress($key, $inputAddr, $this->defaultBranchService, $this->createDefaultTimeInfo(), $this->createDefaultPkgAccept(), $this->createDefaultNoPkgAccept());
		return $getBranchesByAddr;
	}

	public function getPackstationsByAddr($key, $inputAddr)
	{
		$getPackstationsByAddr = new getPackstationsByAddress($key, $inputAddr);
		return $getPackstationsByAddr;
	}
}
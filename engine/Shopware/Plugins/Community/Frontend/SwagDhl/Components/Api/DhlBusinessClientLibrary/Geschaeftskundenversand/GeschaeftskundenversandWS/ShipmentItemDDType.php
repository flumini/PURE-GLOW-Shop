<?php

class ShipmentItemDDType
{

  /**
   * 
   * @var string $PackageType
   * @access public
   */
  public $PackageType = null;

  /**
   * 
   * @param string $PackageType
   * @access public
   */
  public function __construct($PackageType)
  {
    $this->PackageType = $PackageType;
  }

}

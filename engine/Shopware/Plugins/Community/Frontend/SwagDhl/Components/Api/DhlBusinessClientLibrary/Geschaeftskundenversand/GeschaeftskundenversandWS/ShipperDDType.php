<?php

class ShipperDDType
{

  /**
   * 
   * @var string $Remark
   * @access public
   */
  public $Remark = null;

  /**
   * 
   * @param string $Remark
   * @access public
   */
  public function __construct($Remark)
  {
    $this->Remark = $Remark;
  }

}

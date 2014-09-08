<?php

class ShipmentDetailsDDType
{

  /**
   * 
   * @var EKP $EKP
   * @access public
   */
  public $EKP = null;

  /**
   * 
   * @var Attendance $Attendance
   * @access public
   */
  public $Attendance = null;

  /**
   * 
   * @var string $CustomerReference
   * @access public
   */
  public $CustomerReference = null;

  /**
   * 
   * @var string $Description
   * @access public
   */
  public $Description = null;

  /**
   * 
   * @var string $DeliveryRemarks
   * @access public
   */
  public $DeliveryRemarks = null;

  /**
   * 
   * @var ShipmentItemDDType $ShipmentItem
   * @access public
   */
  public $ShipmentItem = null;

  /**
   * 
   * @var ShipmentServiceDD $Service
   * @access public
   */
  public $Service = null;

  /**
   * 
   * @var ShipmentNotificationType $Notification
   * @access public
   */
  public $Notification = null;

  /**
   * 
   * @var string $NotificationEmailText
   * @access public
   */
  public $NotificationEmailText = null;

  /**
   * 
   * @var BankType $BankData
   * @access public
   */
  public $BankData = null;

  /**
   * 
   * @param EKP $EKP
   * @param Attendance $Attendance
   * @param string $CustomerReference
   * @param string $Description
   * @param string $DeliveryRemarks
   * @param ShipmentItemDDType $ShipmentItem
   * @param ShipmentServiceDD $Service
   * @param ShipmentNotificationType $Notification
   * @param string $NotificationEmailText
   * @param BankType $BankData
   * @access public
   */
  public function __construct($EKP, $Attendance, $CustomerReference, $Description, $DeliveryRemarks, $ShipmentItem, $Service, $Notification, $NotificationEmailText, $BankData)
  {
    $this->EKP = $EKP;
    $this->Attendance = $Attendance;
    $this->CustomerReference = $CustomerReference;
    $this->Description = $Description;
    $this->DeliveryRemarks = $DeliveryRemarks;
    $this->ShipmentItem = $ShipmentItem;
    $this->Service = $Service;
    $this->Notification = $Notification;
    $this->NotificationEmailText = $NotificationEmailText;
    $this->BankData = $BankData;
  }

}

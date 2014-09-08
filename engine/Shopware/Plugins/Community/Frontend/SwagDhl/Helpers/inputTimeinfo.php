<?php
namespace Shopware\SwagDhl\Helpers;
class inputTimeinfo
{
	/**
	 *
	 * @var boolean $isOpenedToday
	 * @access public
	 */
	public $isOpenedToday;

	/**
	 *
	 * @var string $weekday
	 * @access public
	 */
	public $weekday;

	/**
	 *
	 * @var string $time
	 * @access public
	 */
	public $time;

	/**
	 *
	 * @param boolean $isOpenedToday
	 * @param string $weekday
	 * @param string $time
	 * @access public
	 */
	public function __construct($isOpenedToday, $weekday, $time)
	{
		$this->isOpenedToday = $isOpenedToday;
		$this->weekday = $weekday;
		$this->time = $time;
	}
}
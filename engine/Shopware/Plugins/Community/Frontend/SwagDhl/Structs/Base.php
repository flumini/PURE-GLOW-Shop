<?php
namespace Shopware\SwagDhl\Structs;

abstract class Base
{
	public function __construct(array $values = array())
	{
		foreach($values as $name => $value) {
			$this->$name = $value;
		}
	}

	public function __get($name)
	{
		throw new \OutOfRangeException("Unknown property \${$name} in " . get_class($this) . ".");
	}

	public function __set($name, $value)
	{
		throw new \OutOfRangeException("Unknown property \${$name} in " . get_class($this) . ".");
	}

	public function __unset($name)
	{
		throw new \OutOfRangeException("Unknown property \${$name} in " . get_class($this) . ".");
	}

	public function __clone()
	{
		foreach($this as $property => $value) {
			if(is_object($value)) {
				$this->$property = clone $value;
			}

			if(is_array($value)) {
				$this->cloneArray($this->$property);
			}
		}
	}

	/**
	 * Clone array
	 *
	 * @param array $array
	 */
	private function cloneArray(array &$array)
	{
		foreach($array as $key => $value) {
			if(is_object($value)) {
				$array[$key] = clone $value;
			}

			if(is_array($value)) {
				$this->cloneArray($array[$key]);
			}
		}
	}
}
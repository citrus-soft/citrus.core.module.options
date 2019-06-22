<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PasswordOption extends OptionControl
{
	/** @var int */
	protected $size;

	public function type()
	{
		return static::TYPE_PASSWORD;
	}

	/**
	 * @param int $value
	 * @return $this|int
	 */
	public function size($value = null)
	{
		return $this->attribute('size', $value);
	}

	protected function getTypeParams()
	{
		return [
			1 => $this->size(),
		];
	}
}
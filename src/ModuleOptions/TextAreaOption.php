<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class TextAreaOption extends OptionControl
{
	/** @var int */
	protected $cols;

	/** @var int */
	protected $rows;

	public function type()
	{
		return static::TYPE_TEXTAREA;
	}

	/**
	 * @param int $value
	 * @return $this|int
	 */
	public function cols($value = null)
	{
		return $this->attribute('cols', $value);
	}

	/**
	 * @param int $value
	 * @return $this|int
	 */
	public function rows($value = null)
	{
		return $this->attribute('rows', $value);
	}

	protected function getTypeParams()
	{
		return [
			1 => $this->rows(),
			2 => $this->cols(),
		];
	}
}
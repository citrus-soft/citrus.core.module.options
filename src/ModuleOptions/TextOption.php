<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class TextOption extends OptionControl
{
	/** @var bool */
	protected $noAutocomplete;

	/** @var int */
	protected $size;

	public function type()
	{
		return static::TYPE_TEXT;
	}

	/**
	 * @param bool $value
	 * @return $this|bool
	 */
	public function noAutocomplete($value = null)
	{
		return $this->attribute('noAutocomplete', $value);
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
			'noautocomplete' => $this->noAutocomplete(),
		];
	}
}
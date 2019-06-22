<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

trait HasDefaultValue
{
	/** @var mixed */
	protected $defaultValue;

	/**
	 * @param mixed $value
	 * @return $this|mixed
	 */
	public function defaultValue($value = null)
	{
		return $this->attribute('defaultValue', $value);
	}

}
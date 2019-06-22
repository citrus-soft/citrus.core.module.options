<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

trait HasDisabled
{
	/** @var bool */
	protected $disabled;

	/**
	 * @param bool $value
	 * @return $this|bool
	 */
	public function disabled($value = null)
	{
		return $this->attribute('disabled', $value);
	}
}
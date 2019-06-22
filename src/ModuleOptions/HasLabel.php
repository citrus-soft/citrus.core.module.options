<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

trait HasLabel
{
	use HasSupText;

	/** @var string */
	protected $label;

	/**
	 * @param string $value
	 * @return $this|string
	 */
	public function label($value = null)
	{
		return $this->attribute('label', $value);
	}

}
<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

trait HasSupText
{
	/** @var string */
	protected $supText;

	/**
	 * @param string $value
	 * @return $this|string
	 */
	public function supText($value = null)
	{
		return $this->attribute('supText', $value);
	}

}
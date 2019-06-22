<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

trait HasName
{
	/** @var string */
	protected $name;

	/**
	 * @param string $value
	 * @return $this|string
	 */
	public function name($value = null)
	{
		return $this->attribute('name', $value);
	}

}
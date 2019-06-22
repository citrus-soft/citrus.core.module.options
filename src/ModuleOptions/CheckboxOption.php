<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class CheckboxOption extends OptionControl
{
	protected $attrs;

	public function type()
	{
		return static::TYPE_CHECKBOX;
	}

	/**
	 * @param string $value
	 * @return $this|string
	 */
	public function attrs($value = null)
	{
		return $this->attribute('attrs', $value);
	}

	protected function getTypeParams()
	{
		return [
			2 => $this->attrs(),
		];
	}
}
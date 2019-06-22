<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class SelectboxOption extends OptionControl
{
	protected $items = [];

	public function type()
	{
		return static::TYPE_SELECTBOX;
	}

	protected function getTypeParams()
	{
		return [
			1 => $this->items(),
		];
	}

	/**
	 * Array of list items
	 *
	 * @param array $value Array keys are item values, values are titles
	 * @return $this|mixed
	 */
	public function items(array $value = null)
	{
		return $this->attribute('items', $value);
	}
}
<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class SelectboxMultiOption extends SelectboxOption
{
	protected $items = [];

	public function type()
	{
		return static::TYPE_SELECTBOX_MULTI;
	}
}
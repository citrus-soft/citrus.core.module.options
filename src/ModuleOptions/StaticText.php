<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class StaticText extends Option
{
	use HasLabel;

	/** @var null */
	protected $text;

	public function __construct($name, $text = null)
	{
		parent::__construct($name);

		$this->text = $text;
	}

	public function type()
	{
		return static::TYPE_STATIC_TEXT;
	}

	/**
	 * @param string $value
	 * @return $this|string
	 */
	public function text($value = null)
	{
		return $this->attribute('text', $value);
	}

	protected function prepareOption(array $aditional = [])
	{
		return parent::prepareOption($aditional + [
				static::PARAM_DEFAULT_VALUE => $this->text()
			]);
	}
}
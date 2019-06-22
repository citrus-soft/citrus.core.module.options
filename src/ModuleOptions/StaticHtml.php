<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class StaticHtml extends Option
{
	use HasLabel;

	/** @var null */
	protected $html;

	public function __construct($name, $text = null)
	{
		parent::__construct($name);

		$this->html = $text;
	}

	public function type()
	{
		return static::TYPE_STATIC_HTML;
	}

	/**
	 * @param string $value
	 * @return $this|string
	 */
	public function html($value = null)
	{
		return $this->attribute('html', $value);
	}

	protected function prepareOption(array $aditional = [])
	{
		return parent::prepareOption($aditional + [
				static::PARAM_DEFAULT_VALUE => $this->html()
			]);
	}

}
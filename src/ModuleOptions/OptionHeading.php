<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class OptionHeading extends Option
{
	use HasLabel;

	/** @var null */
	protected $html;

	public function __construct($label = null)
	{
		parent::__construct('header_' . randString());

		$this->label($label);
	}

	public function type()
	{
		return static::TYPE_HEADING;
	}

	public function render()
	{
		__AdmSettingsDrawRow($this->manager()->getModuleId(), $this->label());
	}
}
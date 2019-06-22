<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

trait HasSiteChoice
{
	/** @var bool */
	protected $siteChoice = false;

	/**
	 * @param bool $value
	 * @return $this|bool
	 */
	public function siteChoice($value = null)
	{
		return $this->attribute('siteChoice', $value);
	}
}
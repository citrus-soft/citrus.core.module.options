<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

interface IModuleOption
{
	public function type();

	public function render();

	public function save();

	/**
	 * @param OptionsManager|null $manager
	 * @return OptionsManager|self
	 */
	public function manager(OptionsManager $manager = null);

	/**
	 * @param string $value
	 * @return string
	 */
	public function name($value = null);
}
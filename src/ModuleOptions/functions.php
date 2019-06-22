<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @param $type
 * @param array $arguments
 * @return IModuleOption
 * @throws \ReflectionException
 */
function option($type, $arguments = [])
{
	return OptionsFactory::getInstance()->create($type, $arguments);
}

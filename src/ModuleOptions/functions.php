<?php

namespace Citrus\Core\ModuleOptions;

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

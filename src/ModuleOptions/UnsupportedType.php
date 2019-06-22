<?php

namespace Citrus\Core\ModuleOptions;

use Throwable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class UnsupportedType extends \RuntimeException
{
	public function __construct($type, $code = 0, Throwable $previous = null)
	{
		parent::__construct(sprintf('Option type %s is not supported', $type), $code, $previous);
	}
}
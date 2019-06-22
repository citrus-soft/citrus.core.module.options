<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

abstract class OptionControl extends Option
{
	use HasDisabled, HasLabel, HasDefaultValue, HasSiteChoice;
}
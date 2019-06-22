<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Citrus\Core\ModuleOptions as Options;

defined('ADMIN_MODULE_NAME');

Loc::loadMessages(__FILE__);

/**
 * @var \CUser $USER
 * @var \CMain $APPLICATION
 */
if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

$moduleOptions = new Options\Manager('citrus.core');
$moduleOptions->addTab(
	(new Options\Tab('edit1', Loc::getMessage("CITRUS_CORE_OPTIONS_TAB1"), Loc::getMessage("CITRUS_CORE_OPTIONS_TAB1_TITLE")))
		->add(new Options\Heading(Loc::getMessage("CITRUS_CORE_OPTIONS_JQUERY_VERSION")))
		->add(new Options\Note(Loc::getMessage("CITRUS_CORE_OPTIONS_JQUERY_VERSION_NOTE")))
		->add((new Options\Selectbox('jquery_version'))
			->siteChoice(true)
			->items([
				'' => Loc::getMessage("CITRUS_CORE_OPTIONS_JQUERY_VERSION_NONE"),
				'jquery2' => Loc::getMessage("CITRUS_CORE_OPTIONS_JQUERY_VERSION_JQUERY2"),
				'citrus.core.jquery-3' => '3.3.1 (citrus.core)',
			])
		)
);

$moduleOptions->show();


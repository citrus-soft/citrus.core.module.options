<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class_alias(CheckboxOption::class, Checkbox::class);
class_alias(NoteOption::class, Note::class);
class_alias(OptionControl::class, Control::class);
class_alias(OptionHeading::class, Heading::class);
class_alias(OptionsManager::class, Manager::class);
class_alias(OptionsTab::class, Tab::class);
class_alias(PasswordOption::class, Password::class);
class_alias(SelectboxMultiOption::class, SelectboxMulti::class);
class_alias(SelectboxOption::class, Selectbox::class);
class_alias(TextAreaOption::class, Textarea::class);
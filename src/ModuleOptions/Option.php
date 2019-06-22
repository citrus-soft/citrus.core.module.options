<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

abstract class Option implements IModuleOption
{
	use HasName;

	const TYPE_NOTE = 'note';
	const TYPE_CHECKBOX = 'checkbox';
	const TYPE_TEXT = 'text';
	const TYPE_PASSWORD = 'password';
	const TYPE_SELECTBOX = 'selectbox';
	const TYPE_SELECTBOX_MULTI = 'multiselectbox';
	const TYPE_TEXTAREA = 'textarea';
	const TYPE_STATIC_TEXT = 'statictext';
	const TYPE_STATIC_HTML = 'statichtml';
	const TYPE_HEADING = '_heading';

	const PARAM_NAME = 0;
	const PARAM_LABEL = 1;
	const PARAM_DEFAULT_VALUE = 2;
	const PARAM_TYPE = 3;
	const PARAM_DISABLED = 4;
	const PARAM_SUP_TEXT = 5;
	const PARAM_SITE_CHOICE = 6;

	/**
	 * @var OptionsManager
	 */
	private $manager;

	public function __construct($name)
	{
		require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/settings.php';

		$this->name($name);
	}

	private function params()
	{
		return [
			static::PARAM_NAME => 'name',
			static::PARAM_LABEL => 'label',
			static::PARAM_DEFAULT_VALUE => 'defaultValue',
			static::PARAM_DISABLED => 'disabled',
			static::PARAM_SUP_TEXT => 'supText',
			static::PARAM_SITE_CHOICE => 'siteChoice',
		];
	}

	protected function prepareOption(array $aditional = [])
	{
		$options = [
			static::PARAM_TYPE => [0 => $this->type()] + $this->getTypeParams(),
		];

		foreach ($this->params() as $param => $method) {
			if (method_exists($this, $method) && null !== $this->$method())
			{
				if ($param == static::PARAM_DISABLED)
				{
					$options[$param] = $this->$method() ? 'Y' : 'N';
				}
				else
				{
					$options[$param] = $this->$method();
				}
			}
			else
			{
				$options[$param] = null;
			}
		}

		return $aditional + $options;
	}

	public function render()
	{
		__AdmSettingsDrawRow($this->manager()->getModuleId(), $this->prepareOption());
	}

	public function save()
	{
		__AdmSettingsSaveOption($this->manager()->getModuleId(), $this->prepareOption());
	}

	public function manager(OptionsManager $manager = null)
	{
		if (isset($manager))
		{
			$this->manager = $manager;

			return $this;
		}

		return $this->manager;
	}

	/**
	 * @return array
	 */
	protected function getTypeParams()
	{
		return [];
	}

	/**
	 * @param string $name
	 * @param mixed|null $value
	 * @return $this|mixed
	 */
	protected function attribute($name, $value = null)
	{
		if (isset($value))
		{
			$this->$name = $value;

			return $this;
		}

		return $this->$name;
	}
}
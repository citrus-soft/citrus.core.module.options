<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class OptionsTab
{
	private $code;
	private $name;
	private $title;

	/** @var IModuleOption[] */
	private $options = [];

	public function __construct($code = null, $name = null, $title = null)
	{
		$this->code = $code;
		$this->name = $name;
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Добавляет одну или несколько настроек на вкладку
	 *
	 * @param IModuleOption[]|IModuleOption $options
	 * @return $this
	 */
	public function add($options)
	{
		if (!is_array($options))
		{
			$options = [$options];
		}

		array_walk($options, function (IModuleOption $option) {

			if (!($name = $option->name()))
			{
				throw new \RuntimeException('Missing option name');
			}

			$this->options[$option->name()] = $option;
		});

		return $this;
	}

	/**
	 * Добавляет одну или несколько настроек если условие $condition выполняется
	 *
	 * @param bool|callable $condition Булево значение или функция, возвращающая его
	 * @param IModuleOption[]|IModuleOption $options
	 * @return $this
	 */
	public function addIf($condition, $options)
	{
		if (is_callable($condition))
		{
			$condition = $condition();
		}

		if ((bool)$condition)
		{
			$this->add($options);
		}

		return $this;
	}

	/**
	 * @return IModuleOption[]
	 */
	public function getOptions()
	{
		return $this->options;
	}

}
<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class OptionsFactory
{
	/** @var string[] */
	private $registeredClasses;
	private static $instance;

	private function __construct()
	{
		$this
			->register(Option::TYPE_CHECKBOX, CheckboxOption::class)
			->register(Option::TYPE_STATIC_TEXT, StaticText::class)
			->register(Option::TYPE_STATIC_HTML, StaticHtml::class)
			->register(Option::TYPE_NOTE, NoteOption::class)
			->register(Option::TYPE_PASSWORD, PasswordOption::class)
			->register(Option::TYPE_SELECTBOX_MULTI, SelectboxMultiOption::class)
			->register(Option::TYPE_SELECTBOX, SelectboxOption::class)
			->register(Option::TYPE_TEXTAREA, TextAreaOption::class)
			->register(Option::TYPE_TEXT, TextOption::class)
		;
	}

	/**
	 * @return self
	 */
	final public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			$classname = self::class;

			self::$instance = new $classname();
		}

		return self::$instance;
	}

	/**
	 * Register option class
	 *
	 * @param string $optionType
	 * @param IModuleOption|string $optionClass
	 * @return $this
	 */
	public function register($optionType, $optionClass)
	{
		if (!$optionClass instanceof IModuleOption)
		{
			throw new \InvalidArgumentException('optionsClass must be instance of \Citrus\Core\ModuleOptions\IModuleOption');
		}

		$this->registeredClasses[$optionType] = $optionClass;

		return $this;
	}

	/**
	 * @param string $optionType
	 * @param array $arguments
	 * @return IModuleOption
	 * @throws \ReflectionException
	 */
	public function create($optionType, $arguments = [])
	{
		if (isset($this->registeredClasses[$optionType]))
		{
			$reflector = new \ReflectionClass($this->registeredClasses[$optionType]);

			/** @noinspection PhpIncompatibleReturnTypeInspection */
			return $reflector->newInstanceArgs($arguments);
		}

		throw new UnsupportedType($optionType);
	}
}
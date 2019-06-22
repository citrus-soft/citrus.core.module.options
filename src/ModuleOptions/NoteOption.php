<?php

namespace Citrus\Core\ModuleOptions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class NoteOption extends Option
{
	/** @var string */
	protected $text;

	public function __construct($text = null)
	{
		parent::__construct('note_' . randString());

		/** @noinspection UnusedConstructorDependenciesInspection */
		$this->text = $text;
	}

	public function type()
	{
		return static::TYPE_NOTE;
	}

	protected function prepareOption(array $aditional = [])
	{
		return parent::prepareOption($aditional + [
			'note' => $this->text(),
		]);
	}

	public function text($value = null)
	{
		return $this->attribute('text', $value);
	}
}
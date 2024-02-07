<?php

namespace Citrus\Core\ModuleOptions;

use Bitrix\Catalog\CatalogIblockTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option as BitrixOption;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\UI\Extension;
use Exception;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

Loc::loadMessages(__FILE__);

/**
 * Вывод настроек для выбора инфоблока, поля элемента и раздела
 *
 * Class IblockPropertiesOption
 * @package Citrus\Core\ModuleOptions
 */
class IblockPropertiesOption extends StaticHtml
{
	/** Префикс в значении Поле элемента */
	const IBLOCK_ELEMENT_FIELD_PREFIX = "IBLOCK_ELEMENT_FIELD_";

	/** Префикс в значении Свойство элемента */
	const IBLOCK_ELEMENT_PROPERTY_PREFIX = "IBLOCK_ELEMENT_PROPERTY_";

	/** Префикс в значении Поле раздела */
	const IBLOCK_SECTION_FIELD_PREFIX = "IBLOCK_SECTION_FIELD_";

	/** Префикс в значении Пользовательское поле раздела */
	const IBLOCK_SECTION_PROPERTY_PREFIX = "IBLOCK_SECTION_PROPERTY_";

	/** Постфикс названия настройки выбранного каталога  */
	const OPTION_IBLOCK_ID_POSTFIX = "iblock_id";

	/** Постфикс названия настройки поля/свойства раздела */
	const OPTION_SECTION_POSTFIX = "section";

	/** Постфикс названия настройки поля/свойства элемента */
	const OPTION_ELEMENT_POSTFIX = "element";

	/** Разделитель при формировании значения */
	const OPTION_VALUE_DELIMETER = "_";

	/** @var bool Загружать ли только инфолблоки, которые являются каталогами */
	private $onlyCatalogIblocks = false;

	/** @var bool Использовать ли список полей элемента инфоблока */
	private $useElementFields = true;

	/** @var bool Использовать ли список свойств элемента инфоблока */
	private $useElementProperties = false;

	/** @var bool Использовать ли CODE вместо ID при выобре свойства */
	private $useElementPropertyCode = false;

	/** @var bool Использовать ли список полей раздела инфоблока */
	private $useSectionFields = true;

	/** @var bool Использовать ли список пользовательских полей раздела инфоблока */
	private $useSectionProperties = false;

	/** @var array[] Список полей для настройки */
	private $options = [];

	/** @var string Заголовок для поля "Поле элемента" */
	private $elementFieldTitle = "";

	/** @var string Заголовок для поля "Поле раздела" */
	private $sectionFieldTitle = "";

	public function __construct($name, $text = null)
	{
		parent::__construct($name);
	}

	/**
	 * Загружать ли только инфолблоки, которые являются каталогами
	 *
	 * @param bool $value
	 * @return $this|string
	 */
	public function setOnlyCatalogIblocks($value = false): IblockPropertiesOption
	{
		$this->onlyCatalogIblocks = $value;

		return $this;
	}

	/**
	 * Возвращает, Загружать ли только инфолблоки, которые являются каталогами
	 *
	 * @return bool
	 */
	public function isOnlyCatalogIblocks(): bool
	{
		return $this->onlyCatalogIblocks;
	}

	/**
	 * Использовать ли список полей элемента инфоблока
	 *
	 * @param bool $useElementFields
	 * @return IblockPropertiesOption
	 */
	public function setUseElementFields(bool $useElementFields): IblockPropertiesOption
	{
		$this->useElementFields = $useElementFields;

		return $this;
	}

	/**
	 * Возвращает, Использовать ли список полей элемента инфоблока
	 *
	 * @return bool
	 */
	public function isUseElementFields(): bool
	{
		return $this->useElementFields;
	}

	/**
	 * Использовать ли список свойств элемента инфоблока
	 *
	 * @param bool $useElementProperties
	 * @return IblockPropertiesOption
	 */
	public function setUseElementProperties(bool $useElementProperties): IblockPropertiesOption
	{
		$this->useElementProperties = $useElementProperties;

		return $this;
	}

	/**
	 * Возвращает, Использовать ли список свойств элемента инфоблока
	 *
	 * @return bool
	 */
	public function isUseElementProperties(): bool
	{
		return $this->useElementProperties;
	}

	/**
	 * Использовать ли CODE вместо ID при выобре свойства
	 *
	 * @param bool $useElementPropertyCode
	 * @return $this
	 */
	public function setUseElementPropertyCode(bool $useElementPropertyCode): IblockPropertiesOption
	{
		$this->useElementPropertyCode = $useElementPropertyCode;

		return $this;
	}

	/**
	 * Возвращает, Использовать ли CODE вместо ID при выобре свойства
	 *
	 * @return bool
	 */
	public function isUseElementPropertyCode(): bool
	{
		return $this->useElementPropertyCode;
	}

	public function getElementPropertyCode() {
		return $this->isUseElementPropertyCode() ? "CODE" : "ID";
	}

	/**
	 * Использовать ли список полей раздела инфоблока
	 *
	 * @param bool $useSectionFields
	 * @return IblockPropertiesOption
	 */
	public function setUseSectionFields(bool $useSectionFields): IblockPropertiesOption
	{
		$this->useSectionFields = $useSectionFields;

		return $this;
	}

	/**
	 * Возвращает, Использовать ли список полей раздела инфоблока
	 *
	 * @return bool
	 */
	public function isUseSectionFields(): bool
	{
		return $this->useSectionFields;
	}

	/**
	 * Использовать ли список пользовательских полей раздела инфоблока
	 *
	 * @param bool $useSectionProperties
	 * @return IblockPropertiesOption
	 */
	public function setUseSectionProperties(bool $useSectionProperties): IblockPropertiesOption
	{
		$this->useSectionProperties = $useSectionProperties;

		return $this;
	}

	/**
	 * Возвращает, Использовать ли список пользовательских полей раздела инфоблока
	 *
	 * @return bool
	 */
	public function isUseSectionProperties(): bool
	{
		return $this->useSectionProperties;
	}

	/**
	 * Установка заголовка для поля "Поле элемента"
	 *
	 * @param $title
	 * @return IblockPropertiesOption
	 */
	public function setElementFieldTitle($title): IblockPropertiesOption
	{
		$this->elementFieldTitle = $title;

		return $this;
	}

	/**
	 * Возвращает заголовок для поля "Поле элемента"
	 *
	 * @return string
	 */
	public function getElementFieldTitle(): string
	{
		return $this->elementFieldTitle ?: Loc::getMessage("MODULE_OPTIONS_IBLOCK_ELEMENT_FIELD_TITLE");
	}

	/**
	 * Установка заголовка для поля "Поле раздела"
	 *
	 * @param $title
	 * @return IblockPropertiesOption
	 */
	public function setSectionFieldTitle($title): IblockPropertiesOption
	{
		$this->sectionFieldTitle = $title;

		return $this;
	}

	/**
	 * Возвращает заголовок для поля "Поле раздела"
	 *
	 * @return string
	 */
	public function getSectionFieldTitle(): string
	{
		return $this->sectionFieldTitle ?: Loc::getMessage("MODULE_OPTIONS_IBLOCK_SECTION_FIELD_TITLE");
	}

	/**
	 * Возвращает ключ настройки Инфоблок
	 *
	 * @return string
	 */
	public function getNameIblock(): string
	{
		return static::getOptionNameIblock($this->name);
	}

	/**
	 * Возвращает ключ настройки Поле элемента
	 *
	 * @return string
	 */
	public function getNameElement(): string
	{
		return static::getOptionNameElement($this->name);
	}

	/**
	 * Возвращает ключ настройки Поле раздела
	 *
	 * @return string
	 */
	public function getNameSection(): string
	{
		return static::getOptionNameSection($this->name);
	}

	/**
	 * @return array[]
	 */
	public function getOptions(): array
	{
		return $this->options;
	}

	/**
	 * @param array[] $options
	 */
	public function setOptions(array $options): void
	{
		$this->options = $options;
	}

	/**
	 * Добавляет одну настройку
	 *
	 * @param $option
	 */
	public function addOption($option)
	{
		$this->options[] = $option;
	}

	/**
	 * Сохранение настроек в модуль
	 */
	public function save()
	{
		try {
			/** Загрузка списка полей */
			$this->loadData();

			/** Сохранение полей каждого отдельно */
			__AdmSettingsSaveOptions($this->manager()->getModuleId(), $this->getOptions());
		}
		catch (Exception $ex) {
			AddMessage2Log(__METHOD__ . ": " . $ex->getMessage());
		}
	}

	/**
	 * Формирование html для вывод настройки
	 */
	public function render()
	{
		try {
			/** Загрузка списка полей */
			$this->loadData();
			/** Вывод полей каждого отдельно */
			__AdmSettingsDrawList($this->manager()->getModuleId(), $this->getOptions());
			/** Загрузка для скрипта фильтрации списка полей */
			Extension::load("jquery");
		}
		catch (Exception $ex) {
			AddMessage2Log(__METHOD__ .": ". $ex->getMessage());
			return "";
		}

		?><script>
			function init<?= $this->getNameIBlock()?>() {

				/** Ключ настройки Выберите инфоблок */
				let IBlockSettingsKey = "<?= $this->getNameIBlock()?>",
					/** Ключ настройки Поле элемента */
					iBlockElementSettingsKey = "<?= $this->getNameElement()?>",
					/** Ключ настройки Поле раздела */
					IBlockSectionSettingsKey = "<?= $this->getNameSection()?>",
					/** Поле Выберите инфоблок */
					IBlockSettingsOb = $("select[name=" + IBlockSettingsKey + "]"),

					/** Фильтровать список полей элемента и раздела по выбранному инфоблоку */
					IBlockSettingsChanged = function (iBlockId) {
						let elementField = $("select[name=" + iBlockElementSettingsKey + "]"),
							sectionField = $("select[name=" + IBlockSectionSettingsKey + "]");

						elementField.children("option[value*=<?=static::IBLOCK_ELEMENT_PROPERTY_PREFIX?>]").hide();
						if (iBlockId) {
							elementField.children("option[value^=<?=static::IBLOCK_ELEMENT_PROPERTY_PREFIX?>" + iBlockId + "]").show();
						}

						sectionField.children("option[value*=<?=static::IBLOCK_SECTION_PROPERTY_PREFIX?>]").hide();
						if (iBlockId) {
							sectionField.children("option[value^=<?=static::IBLOCK_SECTION_PROPERTY_PREFIX?>" + iBlockId + "]").show();
						}
					};

				/**
				 * При изменении выбранного инфоблока фильтровать список полей элемента и раздела по выбранному инфоблоку
				 */
				IBlockSettingsOb.on("change", function () {
					IBlockSettingsChanged($(this).val());
				});

				/**
				 * При загрузке для выбранного инфоблока фильтровать список полей элемента и раздела
				 */
				IBlockSettingsChanged(IBlockSettingsOb.val());
			}

			init<?= $this->getNameIBlock()?>();
		</script>
		<?
	}

	/**
	 * Получение значений настройки
	 *
	 * @param $module - модуль, настройку которого надо получить
	 * @param $name - название настройки
	 * @return array - список значений для полей инфоблока, елемента и раздела инфоблока
	 */
	public static function get($module, $name): array
	{
		$iblockValue = "";
		$elementValue = "";
		$sectionValue = "";

		try {
			$iblockValue = BitrixOption::get($module, static::getOptionNameIblock($name));
			$elementValue = BitrixOption::get($module, static::getOptionNameElement($name), "ID");
			$sectionValue = BitrixOption::get($module, static::getOptionNameSection($name), "ID");
		} catch (Main\ArgumentException $ex) {
		}

		return [
			static::OPTION_IBLOCK_ID_POSTFIX => $iblockValue,
			static::OPTION_SECTION_POSTFIX => static::clearPrefixes($sectionValue, static::IBLOCK_SECTION_FIELD_PREFIX, static::IBLOCK_SECTION_PROPERTY_PREFIX),
			static::OPTION_ELEMENT_POSTFIX => static::clearPrefixes($elementValue, static::IBLOCK_ELEMENT_FIELD_PREFIX, static::IBLOCK_ELEMENT_PROPERTY_PREFIX),
		];
	}

	/**
	 * Возвращает название настройки для выбора Инфоблока
	 *
	 * @param $name
	 * @return string
	 */
	protected static function getOptionNameIblock($name): string
	{
		return static::getOptionName($name, static::OPTION_IBLOCK_ID_POSTFIX);
	}

	/**
	 * Возвращает название настройки для выбора Поля элемента
	 *
	 * @param $name
	 * @return string
	 */
	protected static function getOptionNameElement($name): string
	{
		return static::getOptionName($name, static::OPTION_ELEMENT_POSTFIX);
	}

	/**
	 * Возвращает название настройки для выбора Поля раздела
	 *
	 * @param $name
	 * @return string
	 */
	protected static function getOptionNameSection($name): string
	{
		return static::getOptionName($name, static::OPTION_SECTION_POSTFIX);
	}

	/**
	 * Возвращает название настройки
	 *
	 * @param $name
	 * @param $postfix
	 * @return string
	 */
	protected static function getOptionName($name, $postfix): string
	{
		return $name . "_" . $postfix;
	}

	/**
	 * Убрать префикс типа значения из значения (поле или сво-во элемента/раздела)
	 *
	 * @param $value - значение поля или сво-ва элемента/раздела с прификсом типа значения
	 * @param $fieldPrefix - префикс значения для поля элемента/раздела
	 * @param $propertyPrefix - префикс значения для свойства элемента/раздела
	 * @return string|string[]
	 */
	protected static function clearPrefixes($value, $fieldPrefix, $propertyPrefix)
	{
		if (mb_strpos($value, $fieldPrefix) === 0) {
			return implode("", mb_split($fieldPrefix, $value));
		}

		$value = implode("", mb_split($propertyPrefix, $value));
		return explode(static::OPTION_VALUE_DELIMETER, $value, 2);
	}

	/**
	 * Загрузка и формирование данных для списка опций
	 */
	protected function loadData(): void
	{
		$iblocks = $this->loadIblocks();

		$this->setOptions([]);

		$this->addOption([
			$this->getNameIblock(),
			Loc::getMessage("MODULE_OPTIONS_IBLOCK_TITLE"),
			"",
			[
				Option::TYPE_SELECTBOX,
				$iblocks
			],
			"N",
		]);

		if ($this->isUseElementFields() || $this->useElementProperties) {
			$fields = ["" => Loc::getMessage("MODULE_OPTIONS_IBLOCK_FIELD_PLACEHOLDER")];

			if ($this->isUseElementFields()) {
				$fields = array_merge($fields, $this->loadElementFields());
			}

			if ($this->useElementProperties) {
				$fields = array_merge($fields, $this->loadElementProperties(array_keys($iblocks)));
			}

			$this->addOption([
				$this->getNameElement(),
				$this->getElementFieldTitle(),
				"",
				[
					Option::TYPE_SELECTBOX,
					$fields
				],
				"N",
			]);
		}

		if ($this->isUseSectionFields() || $this->isUseSectionProperties()) {
			$fields = ["" => Loc::getMessage("MODULE_OPTIONS_IBLOCK_FIELD_PLACEHOLDER")];

			if ($this->isUseSectionFields()) {
				$fields = array_merge($fields, $this->loadSectionFields());
			}

			if ($this->isUseSectionProperties()) {
				$fields = array_merge($fields, $this->loadSectionProperties());
			}

			$this->addOption([
				$this->getNameSection(),
				$this->getSectionFieldTitle(),
				"",
				[
					Option::TYPE_SELECTBOX,
					$fields
				],
				"N",
			]);
		}
	}

	/**
	 * Загрузка доступных инфоблоков
	 *
	 * @return string[]
	 */
	protected function loadIBlocks()
	{
		$IBlocks = ["" => Loc::getMessage("MODULE_OPTIONS_IBLOCK_PLACEHOLDER")];

		try {
			if ($this->isOnlyCatalogIBlocks()) {
				$iterator = CatalogIblockTable::getList(
					array(
						"select" => array("IBLOCK_ID", "NAME" => "IBLOCK.NAME"),
						"order" => array("IBLOCK_ID" => "ASC")
					)
				);
			} else {
				$iterator = IblockTable::getList(
					array(
						"select" => array("IBLOCK_ID" => "ID", "NAME"),
						"order" => array("ID" => "ASC")
					)
				);
			}

			while ($row = $iterator->fetch()) {
				$IBlocks[$row["IBLOCK_ID"]] = "[" . $row["IBLOCK_ID"] . "] " . $row["NAME"];
			}
		}
		catch (Exception $ex) {
			AddMessage2Log(__METHOD__ . ": " . $ex->getMessage());
		}

		return $IBlocks;
	}

	/**
	 * Формирование полей элемента инфоблока
	 *
	 * @return array
	 */
	protected function loadElementFields(): array
	{
		return static::getOrmMap(ElementTable::getMap(), static::IBLOCK_ELEMENT_FIELD_PREFIX);
	}

	/**
	 * Формирование полей раздела инфоблока
	 *
	 * @return array
	 */
	protected function loadSectionFields(): array
	{
		return static::getOrmMap(SectionTable::getMap(), static::IBLOCK_SECTION_FIELD_PREFIX);
	}

	/**
	 * Загрузка свойств загруженных инфоблоков
	 *
	 * @param $IBlocks
	 * @return array
	 */
	protected function loadElementProperties($IBlocks)
	{
		$elementProps = [];

		if (!$IBlocks)
			return [];


		$elementPropertyField = $this->getElementPropertyCode();
		try {
			$propertyIterator = PropertyTable::getList(
				[
					"select" => [$elementPropertyField, "IBLOCK_ID", "NAME"],
					"filter" => ["=IBLOCK_ID" => $IBlocks],
					"order" => ["IBLOCK_ID" => "ASC", "ID" => "ASC"]
				]
			);
			while ($property = $propertyIterator->fetch()) {
				$elementProps[static::IBLOCK_ELEMENT_PROPERTY_PREFIX . $property["IBLOCK_ID"] . "_" . $property[$elementPropertyField]] = "[" . $property[$elementPropertyField] . "] " . $property["NAME"];
			}
		}
		catch (Exception $ex) {
			AddMessage2Log(__METHOD__ . ": " . $ex->getMessage());
		}

		return $elementProps;
	}

	/**
	 * Загрузка пользовательских полей разделов инфоблоков
	 *
	 * @return array
	 */
	protected function loadSectionProperties()
	{
		$sectionProps = [];

		try {
			$ufIterator = \CUserTypeEntity::GetList(
				["ENTITY_ID" => "ASC"],
				["LANG" => LANGUAGE_ID]
			);
			while($uf = $ufIterator->Fetch())
			{
				$matches = null;
				preg_match("/IBLOCK_(\d*)_SECTION/", $uf["ENTITY_ID"], $matches);
				if (!is_array($matches) || count($matches) < 2) {
					continue;
				}

				$iblockId = $matches[1];
				$sectionProps[static::IBLOCK_SECTION_PROPERTY_PREFIX . $iblockId . "_" . $uf["FIELD_NAME"]] = "[" . $uf["FIELD_NAME"] . "] " . ($uf["EDIT_FORM_LABEL"] ?: $uf["FIELD_NAME"]);
			}
		}
		catch (Exception $ex) {
			AddMessage2Log(__METHOD__ . ": " . $ex->getMessage());
		}

		return $sectionProps;
	}

	/**
	 * Формирование полей таблицы
	 *
	 * @param $map - Map таблицы ORM
	 * @param string $prefix - префикс для формирования ключа в массиве
	 * @return array - список полей по переданному mapping`у
	 */
	protected static function getOrmMap($map, $prefix = ""): array
	{
		$fields = [];

		/**
		 * @var array|Field $field
		 */
		foreach ($map as $key => $field) {
			if ($field instanceof ReferenceField || (is_array($field) && isset($field["reference"]))) {
				continue;
			}

			$name = is_array($field) ? $key : $field->getName();
			$title = is_array($field) ? ($field["title"] ?: $key) : $field->getTitle();

			$fields[$prefix . $name] = "[" . $name . "] " . $title;
		}

		return $fields;
	}
}

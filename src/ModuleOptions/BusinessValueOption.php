<?php

namespace Citrus\Core\ModuleOptions;

use Bitrix\Main;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\BusinessValueControl;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

Loc::loadMessages(__FILE__);

/**
 * Поле для настройки бизнес-смыслов для соответствия свойств заказа
 *
 * Class BusinessValueOption
 * @package Citrus\Core\ModuleOptions
 */
class BusinessValueOption extends StaticHtml
{
	/** @var string Ключ настройки бизнес-смыслов */
	private $consumer_key = null;

	/** @var Callback с описанием бизнес-смыслов */
	private $callback;

	/** @var BusinessValueControl Объект для работы с бизнес-смыслами */
	private $businessValueControl;

	/**
	 * Инициализация данных для подключения бизнес-смыслов
	 *
	 * BusinessValueOption constructor.
	 * @param $name
	 * @param $consumer_key
	 * @param $businessValueConsumersCallback
	 */
	public function __construct($name, $consumer_key, $businessValueConsumersCallback)
	{
		parent::__construct($name);

		$this->consumer_key = $consumer_key;
		$this->callback = $businessValueConsumersCallback;

		$eventManager = EventManager::getInstance();
		$eventManager->addEventHandler("sale", "OnGetBusinessValueConsumers", $this->callback);

		if (!class_exists("\\Bitrix\\Sale\\Helpers\\Admin\\BusinessValueControl")) {
			require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/sale/lib/helpers/admin/businessvalue.php");
		}

		$this->businessValueControl = new BusinessValueControl($this->consumer_key);

		$this->renderHtml();
	}

	/**
	 * Формирование html для вывод настройки
	 */
	protected function renderHtml()
	{
		ob_start();
		?><tr>
			<td colspan="2"><?
				$this->businessValueControl->renderMap(array("CONSUMER_KEY" => $this->consumer_key, "HIDE_FILLED_CODES" => false));
			?></td>
		</tr><?
		$this->html(ob_get_clean());
	}

	/**
	 * Сохранение настроек
	 *
	 * @throws Main\SystemException
	 */
	public function save()
	{
		if ($isSuccess = $this->businessValueControl->setMapFromPost()) {
			$this->businessValueControl->saveMap();
		}
	}
}
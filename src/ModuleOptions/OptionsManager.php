<?php

namespace Citrus\Core\ModuleOptions;

use Bitrix\Main\Config\Option as BitrixOption;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class OptionsManager
{
	/** @var string */
	protected $moduleId;

	/** @var OptionsTab[] */
	protected $tabs;

	/** @var \Bitrix\Main\HttpRequest */
	private $request;

	public function __construct($moduleId)
	{
		$this->moduleId = $moduleId;

		$this->request = Context::getCurrent()->getRequest();

		Loc::loadMessages(Application::getDocumentRoot() . BX_ROOT . "/modules/main/options.php");
	}

	/**
	 * @return mixed
	 */
	public function getModuleId()
	{
		return $this->moduleId;
	}

	/**
	 * @param OptionsTab $tab
	 * @return $this
	 */
	public function addTab(OptionsTab $tab)
	{
		$this->tabs[$tab->getCode()] = $tab;

		return $this;
	}

	public function show()
	{
		$this->bindOptions();

		$this->processActions();

		$this->render();
	}

	protected function render()
	{
		$tabs = [];
		foreach ($this->tabs as $tab)
		{
			$tabs[] = [
				"DIV" => $tab->getCode(),
				"TAB" => $tab->getName(),
				"TITLE" => $tab->getTitle(),
			];
		}
		$tabControl = $this->tabControl = new \CAdminTabControl("tabControl", $tabs);

		$tabControl->Begin();

		?><form method="post"
		      action="<?=sprintf('%s?mid=%s&lang=%s', $this->request->getRequestedPage(), urlencode($this->request->get('mid')), LANGUAGE_ID)?>">
			<?php

			echo bitrix_sessid_post();

			foreach ($this->tabs as $tab)
			{
				$tabControl->BeginNextTab();

				foreach ($tab->getOptions() as $option)
				{
					$option->render();
				}

			}

			$tabControl->Buttons();

			?>
			<input type="submit"
			       name="save"
			       value="<?=Loc::getMessage("MAIN_SAVE")?>"
			       title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE")?>"
			       class="adm-btn-save"
			/>
			<input type="submit"
			       name="restore"
			       title="<?=Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")?>"
			       onclick="return confirm('<?=addslashes(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')"
			       value="<?=Loc::getMessage("MAIN_RESTORE_DEFAULTS")?>"
			/>
			<?php
			$tabControl->End();
			?>
		</form><?php

	}

	public function processActions()
	{
		$request = $this->request;

		list($save, $apply, $restore) = [
			$request->getPost('save'),
			$request->getPost('apply'),
			$request->getPost('restore'),
		];

		if (($save || $restore || $apply)
			&& $request->isPost()
			&& check_bitrix_sessid())
		{
			if ($restore)
			{
				BitrixOption::delete($this->getModuleId());
			}
			else
			{
				foreach ($this->tabs as $tab)
				{
					foreach ($tab->getOptions() as $option)
					{
						$option->save();
					}
				}
			}

			$backUrl = $request->get('back_url_settings');

			if ($save && strlen($backUrl) > 0)
			{
				LocalRedirect($backUrl);
			}
			else
			{
			    global $APPLICATION;

				LocalRedirect(
					$APPLICATION->GetCurPage() .
					'?mid=' . urlencode($this->getModuleId()) .
					'&lang=' . urlencode(LANGUAGE_ID) .
                    ($backUrl
					    ? '&back_url_settings=' . urlencode($backUrl)
                        : '') .
                    ($this->tabControl
                        ? '&' . $this->tabControl->ActiveTabParam()
                        : '')
                );
			}
		}

	}

	private function bindOptions()
	{
		foreach ($this->tabs as $tab)
		{
			foreach ($tab->getOptions() as $option)
			{
				$option->manager($this);
			}
		}
	}
}
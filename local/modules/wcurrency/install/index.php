<?php
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;

class WCurrency extends CModule
{

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__.'/version.php';

        $this->MODULE_ID = 'wcurrency';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = 'Модуль курсов валют';
        $this->MODULE_DESCRIPTION = 'Модуль для работы с курсами валют';
        $this->PARTNER_NAME = '';
        $this->PARTNER_URI = '';
    }

    public function DoInstall()
    {
        global $APPLICATION;
        if (ModuleManager::isModuleInstalled('wcurrency')) {
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallAgents();

        $APPLICATION->IncludeAdminFile(
            'Установка модуля "Курс валют"',
            __DIR__ . '/step.php'
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallAgents();
        ModuleManager::unRegisterModule($this->MODULE_ID);


        $APPLICATION->IncludeAdminFile(
            'Удаление модуля "Курс валют"',
            __DIR__ . '/unstep.php'
        );

    }

    public function InstallDB()
    {
        global $APPLICATION;
        global $DB;
        $this->errors = false;

        if (!$DB->Query("SELECT 'x' FROM wc_currency LIMIT 0", true)) {
            $this->errors = $DB->RunSQLBatch(__DIR__ . '/db/mysql/install.sql');
        }

        if ($this->errors !== false) {
            $APPLICATION->ThrowException(implode('<br>', $this->errors));
            return false;
        }

        return true;
    }

    public function UnInstallDB()
    {
        global $APPLICATION;
        global $DB;
        $this->errors = false;

        if ($DB->Query("SELECT 'x' FROM wc_currency LIMIT 0", true)) {
            $this->errors = $DB->RunSQLBatch(__DIR__ . '/db/mysql/uninstall.sql');
        }

        if ($this->errors !== false) {
            $APPLICATION->ThrowException(implode('<br>', $this->errors));
            return false;
        }

        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . '/components',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/wcurrency',
            true,
            true
        );

        CopyDirFiles(
            __DIR__ . '/admin',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin',
            true,
            true
        );

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx('/bitrix/components/wcurrency');

        DeleteDirFiles(
            __DIR__ . '/admin',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
        );

        return true;
    }


    public function InstallAgents()
    {
        \CAgent::AddAgent('\WCurrency\Agent::updateCurrencyRates();', 'wcurrency', 'N', 86400);
        return true;
    }

    public function UnInstallAgents()
    {
        \CAgent::RemoveAgent('\WCurrency\Agent::updateCurrencyRates();', 'wcurrency');
        return true;
    }
}

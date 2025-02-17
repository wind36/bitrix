<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use WCurrency\CurrencyTable;

class CurrencyListComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        // Установка значений по умолчанию
        $arParams['FILTER_NAME'] = isset($arParams['FILTER_NAME']) ? $arParams['FILTER_NAME'] : 'arrCurrencyFilter';
        $arParams['PAGE_SIZE'] = isset($arParams['PAGE_SIZE']) ? intval($arParams['PAGE_SIZE']) : 50;
        return $arParams;
    }

    public function executeComponent()
    {
        // Подключение модуля
        if (!Loader::includeModule('wcurrency')) {
            ShowError(GetMessage('MODULE_NOT_INSTALLED'));
            return;
        }

        // Получение фильтра из глобальной переменной
        global ${$this->arParams['FILTER_NAME']};
        $filter = ${$this->arParams['FILTER_NAME']} ?? [];

        // Получение данных из базы
        $nav = new \Bitrix\Main\UI\PageNavigation("page");
        $nav->allowAllRecords(false)
            ->setPageSize($this->arParams['PAGE_SIZE'])
            ->initFromUri();

        $result = CurrencyTable::getList([
            'filter' => $filter,
            'order' => ['DATE' => 'DESC'],
            'limit' => $nav->getLimit(),
            'offset' => $nav->getOffset(),
            'count_total' => true,
        ]);

        $nav->setRecordCount($result->getCount());

        $this->arResult['ITEMS'] = $result->fetchAll();
        $this->arResult['NAVIGATION'] = $nav;

        // Вывод шаблона
        $this->includeComponentTemplate();
    }
}

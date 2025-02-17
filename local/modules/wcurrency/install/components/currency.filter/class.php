<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;

class CurrencyFilterComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        // Установка значений по умолчанию
        $arParams['FILTER_NAME'] = isset($arParams['FILTER_NAME']) ? $arParams['FILTER_NAME'] : 'arrCurrencyFilter';
        return $arParams;
    }

    public function executeComponent()
    {
        // Подключение модуля
        if (!Loader::includeModule('wcurrency')) {
            ShowError(GetMessage('MODULE_NOT_INSTALLED'));
            return;
        }

        // Получение данных из запроса
        $request = Context::getCurrent()->getRequest();
        if ($request->isPost() && $request->getPost('filter')) {
            $this->processFilter($request->getPost('filter'));
        }

        // Получение текущих значений фильтра
        global ${$this->arParams['FILTER_NAME']};
        $this->arResult['FILTER'] = ${$this->arParams['FILTER_NAME']} ?? [];

        // Вывод шаблона
        $this->includeComponentTemplate();
    }

    private function processFilter($filterData)
    {
        // Формирование массива фильтра
        $filter = [];
        if (!empty($filterData['CODE'])) {
            $filter['CODE'] = trim($filterData['CODE']);
        }
        if (!empty($filterData['DATE_FROM'])) {
            $filter['>=DATE'] = new \Bitrix\Main\Type\DateTime($filterData['DATE_FROM'] . ' 00:00:00', "Y-m-d H:i:s");

        }
        if (!empty($filterData['DATE_TO'])) {
            $filter['<=DATE'] = new \Bitrix\Main\Type\DateTime($filterData['DATE_TO'] . ' 23:59:59', "Y-m-d H:i:s");
        }
        if (!empty($filterData['COURSE_FROM'])) {
            $filter['>=COURSE'] = floatval($filterData['COURSE_FROM']);
        }
        if (!empty($filterData['COURSE_TO'])) {
            $filter['<=COURSE'] = floatval($filterData['COURSE_TO']);
        }

        // Сохранение фильтра в глобальную переменную
        global ${$this->arParams['FILTER_NAME']};
        ${$this->arParams['FILTER_NAME']} = $filter;
    }
}

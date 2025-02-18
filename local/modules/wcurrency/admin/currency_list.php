<?php
// Подключаем пролог административной части до выполнения основной логики
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

// Используем класс CurrencyTable из модуля WCurrency
use WCurrency\CurrencyTable;
use Bitrix\Main\Context;

global $APPLICATION;

$APPLICATION->SetTitle('Список курсов валют');

$request = Context::getCurrent()->getRequest();


if ($request->get('action') === 'delete' && check_bitrix_sessid()) {
    $id = $request->get('id');
    if ($id) {
        CurrencyTable::delete($id);
    }
}

$rsData = CurrencyTable::getList(['order' => ['DATE' => 'DESC']]);
$data = [];
while ($item = $rsData->fetch()) {
    $data[] = $item;
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

$listId = 'currency_list';


$adminList = new CAdminList($listId);

$adminList->DisplayList();


require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');

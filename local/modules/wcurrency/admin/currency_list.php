<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

use WCurrency\CurrencyTable;
global $APPLICATION;
$APPLICATION->SetTitle('Список курсов валют');

if ($_REQUEST['action'] === 'delete' && check_bitrix_sessid()) {
    CurrencyTable::delete($_REQUEST['id']);
}

$rsData = CurrencyTable::getList(['order' => ['DATE' => 'DESC']]);
$data = [];
while ($item = $rsData->fetch()) {
    $data[] = $item;
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

$adminList = new CAdminList($listId);
$adminList->DisplayList();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');

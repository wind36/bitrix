<?php
// пространство имен для подключений языковых файлов
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

// проверка идентификатора сессии
if (!check_bitrix_sessid()) {
    return;
}
global $APPLICATION;
// проверяем была ли выброшена ошибка при установке, если да, то записываем её в переменную $errorException
if ($errorException = $APPLICATION->getException()) {

    // вывод сообщения об ошибке при установке модуля
    CAdminMessage::showMessage(
        Loc::getMessage('MODULE_INSTALL_ERROR') . ': ' . $errorException->GetString()
    );
} else {
    // вывод уведомления при успешной установке модуля
    CAdminMessage::showNote(
        Loc::getMessage('MODULE_INSTALL_SUCCESS')
    );
}
?>
<form action="<?= $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="submit" name="" value="<?= Loc::getMessage("MOD_BACK") ?>">
</form>

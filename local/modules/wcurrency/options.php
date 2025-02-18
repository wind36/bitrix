<?php
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;

// Идентификатор модуля
$module_id = 'wcurrency';

$request = Context::getCurrent()->getRequest();

if ($request->isPost() && $request->getPost('save') !== null && check_bitrix_sessid()) {
    $update_interval = $request->getPost('update_interval');
    Option::set($module_id, 'update_interval', $update_interval);
}

$update_interval = Option::get($module_id, 'update_interval', 86400);
?>


<form method="post">
    <?= bitrix_sessid_post() ?>
    <label for="update_interval">Интервал обновления курсов (в секундах):</label>
    <input 
        type="number" 
        id="update_interval" 
        name="update_interval" 
        value="<?= htmlspecialcharsbx($update_interval) ?>" 
        min="1"
    >
    <button type="submit" name="save">Сохранить</button>
</form>

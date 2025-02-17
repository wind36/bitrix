<?php

use Bitrix\Main\Config\Option;

$module_id = 'wcurrency';

if ($REQUEST_METHOD == 'POST' && $save <> '' && check_bitrix_sessid()) {
    Option::set($module_id, 'update_interval', $_POST['update_interval']);
}

$update_interval = Option::get($module_id, 'update_interval', 86400);

?>
<form method="post">
    <?= bitrix_sessid_post() ?>
    <label for="update_interval">Интервал обновления курсов (в секундах):</label>
    <input type="number" name="update_interval" value="<?= htmlspecialcharsbx($update_interval) ?>">
    <button type="submit" name="save">Сохранить</button>
</form>

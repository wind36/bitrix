<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

// Регистрация обработчиков событий
EventManager::getInstance()->registerEventHandler(
    'main',                           // Модуль, который генерирует событие
    'OnBeforeProlog',                 // Событие
    'wcurrency',                      // ID модуля
    '\Wcurrency\EventHandlers',       // Пространство имен и класс обработчика
    'onBeforeProlog'                  // Метод обработчика
);

Loader::registerAutoLoadClasses('wcurrency', array(
    '\WCurrency\CurrencyTable' =>	'lib/CurrencyTable.php',
    '\WCurrency\Agent' => 'lib/Agent.php',
));


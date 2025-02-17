<?php
namespace WCurrency;

use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

class Agent
{
    public static function updateCurrencyRates()
    {
        Loader::includeModule('wcurrency');

        $url = 'https://www.cbr.ru/scripts/XML_daily.asp';
        $xml = simplexml_load_file($url);
        $date = new DateTime(); // Текущая дата и время

        foreach ($xml->Valute as $valute) {
            $code = (string)$valute->CharCode;
            $course = str_replace(',', '.', (string)$valute->Value);

            // Добавление данных в таблицу
            CurrencyTable::add([
                'CODE' => $code,
                'DATE' => $date,
                'COURSE' => $course,
            ]);

        }

        return '\WCurrency\Agent::updateCurrencyRates();';
    }
}

<?php
namespace WCurrency;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class CurrencyTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'wc_currency';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new Entity\StringField('CODE', [
                'required' => true,
            ]),
            new Entity\DatetimeField('DATE', [
                'required' => true,
            ]),
            new Entity\FloatField('COURSE', [
                'required' => true,
            ]),
        ];
    }
}

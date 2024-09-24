<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Infrastructure\Utils;

use TFM\HolidaysManagement\Country\Application\PostalCode\PostalCodeResponse;

final class Formatter
{

    public static function formatterKeyValue (array $array) : array
    {
        return Formatter::format($array, true);
    }


    public static function formatterKeyValueCountriesForms (array $array) : array
    {
        return Formatter::format($array, false);
    }

    public static function format (array $array, bool $isIdentUuid) : array
    {
        $newArrayKeyValue = [];

        if (!empty($array)) {
            foreach ($array as $item) {
                if ($isIdentUuid ) {
                    $newArrayKeyValue[$item->name()] = $item->id()->value();
                } else {
                    if ($item instanceof PostalCodeResponse) {
                        $newArrayKeyValue[$item->value()] = $item->value();
                    } else {
                        $newArrayKeyValue[$item->name()] = $item->id();
                    }
                }
            }
        }

        return $newArrayKeyValue;
    }
}

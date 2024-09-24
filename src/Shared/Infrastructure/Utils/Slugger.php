<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Infrastructure\Utils;

use TFM\HolidaysManagement\Shared\Infrastructure\Utils\Sluggers\CocurSlugger;

class Slugger
{
    private static $slugger;

    public static function slugify(string $string): string
    {
        if (self::$slugger === null) {
            self::$slugger = CocurSlugger::create();
        }
        return self::$slugger->slugify($string);
    }
}

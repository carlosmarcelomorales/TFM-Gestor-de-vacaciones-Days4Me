<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\Shared\Infrastructure\Utils\Sluggers;

use Cocur\Slugify\Slugify;
use TFM\HolidaysManagement\Shared\Infrastructure\Utils\SluggerInterface;


class CocurSlugger implements SluggerInterface
{
    private static Slugify $slugger;

    private function __construct()
    {
        self::$slugger = Slugify::create();
    }

    public function slugify(string $string): string
    {
        return self::$slugger->slugify($string);
    }

    public static function create(): SluggerInterface
    {
        return new self();
    }
}

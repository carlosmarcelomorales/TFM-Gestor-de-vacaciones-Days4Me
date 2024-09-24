<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Infrastructure\Utils;

interface SluggerInterface
{
    public function slugify(string $string): string;

    public static function create(): SluggerInterface;
}

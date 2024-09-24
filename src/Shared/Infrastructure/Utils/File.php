<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Infrastructure\Utils;


use Exception;
use InvalidArgumentException;

final class File
{
    public static function checkIsDocument(string $document): bool
    {
        if (@is_array(filesize($document))) {
            return true;
        }
        return false;
    }

    public static function checkDirectory($directoryToCheck): string
    {
        try {
            if (!file_exists($directoryToCheck)) {
                mkdir($directoryToCheck, 0777, true);
            }
        } catch (Exception $e) {
            throw new InvalidArgumentException(sprintf('Has error occurred contact with the administrator!! : %s!!!',
                                                       $e->getMessage()));
        }
        return $directoryToCheck;
    }

    public static function toSnakeCase(string $text): string
    {
        return lcfirst(str_replace(' ', '_', $text));
    }
}

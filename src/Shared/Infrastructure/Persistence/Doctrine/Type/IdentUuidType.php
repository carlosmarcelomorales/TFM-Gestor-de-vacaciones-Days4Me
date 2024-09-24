<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class IdentUuidType extends StringType
{
    public const NAME = 'uuid';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $id = parent::convertToPHPValue($value, $platform);

        return $id !== null ? new IdentUuid($id) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string)$value;
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

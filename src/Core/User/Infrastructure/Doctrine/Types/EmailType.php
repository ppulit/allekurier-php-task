<?php

namespace App\Core\User\Infrastructure\Doctrine\Types;

use App\Core\User\Domain\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EmailType extends Type
{
    public const NAME = 'email';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        if (null === $value) {
            return null;
        }

        return new Email($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Email) {
            throw new \InvalidArgumentException('Expected '.Email::class.', got '.gettype($value));
        }

        return $value->getValue();
    }

    public function getName(): string
    {
        return self::NAME;
    }
}

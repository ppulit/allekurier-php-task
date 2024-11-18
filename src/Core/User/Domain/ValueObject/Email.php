<?php

namespace App\Core\User\Domain\ValueObject;

use App\Core\User\Domain\Exception\InvalidEmailException;

class Email
{
    private string $value;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException('Niepoprawny adres email');
        }

        if (strlen($email) > 300) {
            throw new InvalidEmailException('Adres email jest zbyt długi');
        }

        $this->value = $email;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

<?php

namespace App\Core\User\Application\Query\GetEmailsByStatus;

class GetEmailsByActivityQuery
{
    public function __construct(public readonly string $isActive)
    {
    }
}

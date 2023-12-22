<?php

declare(strict_types=1);

namespace App\Event\Client;

use App\Client\Entity\Id;

readonly class EmailVerifiedEvent
{
    public function __construct(public Id $clientId)
    {
    }
}

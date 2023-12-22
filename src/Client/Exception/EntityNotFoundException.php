<?php

declare(strict_types=1);

namespace App\Client\Exception;

use App\Client\Entity\Id;

class EntityNotFoundException extends \DomainException
{
    public function __construct(Id $id, string $message = 'Entity "Client" not found.')
    {
        parent::__construct($message . ' Id: ' . $id->getValue());
    }
}

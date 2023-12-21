<?php

declare(strict_types=1);

namespace App\Client\Repository;

use App\Client\Entity\Client;
use App\Client\Entity\Id;

interface ClientRepositoryInterface
{
    public function add(Client $entity): void;

    public function remove(Client $entity): void;

    public function get(Id $id): Client;
}

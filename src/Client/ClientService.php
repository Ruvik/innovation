<?php

declare(strict_types=1);

namespace App\Client;

use App\Client\Entity\Client;
use App\Client\Entity\Id;
use App\Client\Repository\ClientRepositoryInterface;

class ClientService
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(private ClientRepositoryInterface $clientRepository)
    {
    }

    public function get(Id $id): Client
    {
        return $this->clientRepository->get($id);
    }

    public function add(Client $client): void
    {
        $this->clientRepository->add($client);
    }
}

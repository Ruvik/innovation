<?php

declare(strict_types=1);

namespace App\ClientReward\Repository;

use App\Bonus\Entity\Id;
use App\ClientReward\Entity\ClientReward;
use Doctrine\ORM\EntityNotFoundException;

interface ClientRewardRepositoryInterface
{
    public function add(ClientReward $entity): void;

    /**
     * @throws EntityNotFoundException
     */
    public function get(Id $id): ClientReward;

    /**
     * @param \App\Client\Entity\Id $clientId
     * @param int $limit
     * @param int $offset
     * @return ClientReward[]
     */
    public function getRewardsByClientId(\App\Client\Entity\Id $clientId, int $limit = 1000, int $offset = 0): array;

    public function getCountRewardsByClientId(\App\Client\Entity\Id $clientId): int;
}

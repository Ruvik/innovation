<?php

declare(strict_types=1);

namespace App\ClientReward;

use App\Client\Entity\Id;
use App\ClientReward\Entity\ClientReward;
use App\ClientReward\Repository\ClientRewardRepositoryInterface;

class ClientRewardService
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(private ClientRewardRepositoryInterface $clientRewardRepository)
    {
    }

    public function add($entity): void
    {
        $this->clientRewardRepository->add($entity);
    }

    /**
     * @param Id $clientId
     * @param int $limit
     * @param int $offset
     * @return ClientReward[]
     */
    public function getRewardsByClientId(Id $clientId, int $limit = 1000, int $offset = 0): array
    {
        return $this->clientRewardRepository->getRewardsByClientId($clientId, $limit, $offset);
    }

    public function getCountRewardsByClientId(\App\Client\Entity\Id $clientId): int
    {
        return $this->clientRewardRepository->getCountRewardsByClientId($clientId);
    }
}

<?php

declare(strict_types=1);

namespace App\Client\UseCase;

use App\Bonus\BonusService;
use App\ClientReward\ClientRewardService;

readonly class RewardsFetcher
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(private ClientRewardService $clientRewardService, private BonusService $bonusService)
    {
    }

    public function fetch(RewardsQuery $query): array
    {
        $rewards = $this->clientRewardService
            ->getRewardsByClientId($query->id, $query->limit, ($query->page * $query->limit) - $query->limit);
        $bonusIds = array_map(fn($reward) => $reward->getBonusId(), $rewards);

        $responseData = [];
        foreach ($rewards as $reward) {
            $responseData[$reward->getBonusId()->getValue()] = [
                'createdAt' => $reward->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }
        foreach ($this->bonusService->getByIds(...$bonusIds) as $bonus) {
            $responseData[$bonus->getId()->getValue()]['type'] = $bonus->getTypeName();
            $responseData[$bonus->getId()->getValue()]['name'] = $bonus->getName();
        }

        return [
            'data' => array_values($responseData),
            'meta' => [
                'total' => $this->clientRewardService->getCountRewardsByClientId($query->id),
                'limit' => $query->limit,
                'page' => $query->page,
            ],
        ];
    }
}

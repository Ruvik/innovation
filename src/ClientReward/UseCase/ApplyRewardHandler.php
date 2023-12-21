<?php

declare(strict_types=1);

namespace App\ClientReward\UseCase;

use App\Bonus\BonusService;
use App\ClientReward\ClientRewardService;
use App\ClientReward\Entity\ClientReward;
use App\ClientReward\Entity\Id;
use App\Flusher;

readonly class ApplyRewardHandler
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private BonusService $bonusService,
        private ClientRewardService $clientRewardService,
        private Flusher $flusher
    ) {
    }

    /**
     * @param ApplyRewardCommand $command
     * @return string[]
     */
    public function handle(ApplyRewardCommand $command): array
    {
        $bonusIds = $this->bonusService->getIdsByType($command->rewardType);
        $appliedBonusIds = [];
        foreach ($bonusIds as $bonusId) {
            $clientReward = new ClientReward(Id::generate(), $command->clientId, $bonusId);
            $this->clientRewardService->add($clientReward);
            $appliedBonusIds[] = $bonusId->getValue();
        }
        $this->flusher->flush();
        return $appliedBonusIds;
    }
}

<?php

declare(strict_types=1);

namespace App\ClientReward\UseCase;

use App\Bonus\Entity\Type;
use App\Client\Entity\Id;

readonly class ApplyRewardCommand
{
    public function __construct(
        public Id $clientId,
        public Type $rewardType,
    ) {
    }
}

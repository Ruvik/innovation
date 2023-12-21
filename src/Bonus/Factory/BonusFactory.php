<?php

declare(strict_types=1);

namespace App\Bonus\Factory;

use App\Bonus\Entity\Bonus;
use App\Bonus\Entity\Hug;
use App\Bonus\Entity\Id;
use App\Bonus\Entity\Type;
use App\Bonus\Entity\Smile;
use App\Bonus\UseCase\AddCommand;

class BonusFactory
{
    public function create(AddCommand $command): Bonus
    {
        return match ($command->rewardType) {
            Type::HUG => new Hug(Id::generate(), $command->name),
            Type::SMILE => new Smile(Id::generate(), $command->name),
            default => throw new \InvalidArgumentException('Unknown reward type')
        };
    }
}

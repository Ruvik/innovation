<?php

declare(strict_types=1);

namespace App\Bonus\UseCase;

use App\Bonus\Entity\Type;

readonly class AddCommand
{
    public function __construct(public string $name, public Type $rewardType)
    {
    }
}

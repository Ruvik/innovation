<?php

declare(strict_types=1);

namespace App\Bonus;

use App\Bonus\Entity\Bonus;
use App\Bonus\Entity\Id;
use App\Bonus\Entity\Type;
use App\Bonus\Repository\BonusRepositoryInterface;

class BonusService
{
    public function __construct(readonly private BonusRepositoryInterface $bonusRepository)
    {
    }

    /**
     * @param Type $type
     * @return Id[]
     */
    public function getIdsByType(Type $type): array
    {
        return $this->bonusRepository->getIdsByType($type);
    }

    /**
     * @param Id ...$ids
     * @return Bonus[]
     */
    public function getByIds(Id ...$ids): array
    {
        return $this->bonusRepository->getByIds(...$ids);
    }
}

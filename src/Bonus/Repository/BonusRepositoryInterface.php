<?php

declare(strict_types=1);

namespace App\Bonus\Repository;

use App\Bonus\Entity\Bonus;
use App\Bonus\Entity\Id;
use App\Bonus\Entity\Type;

interface BonusRepositoryInterface
{
    public function add(Bonus $entity): void;

    /**
     * @param Type $type
     * @return Id[]
     */
    public function getIdsByType(Type $type): array;

    /**
     * @param Id ...$ids
     * @return Bonus[]
     */
    public function getByIds(Id ...$ids): array;
}

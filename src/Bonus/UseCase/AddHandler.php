<?php

declare(strict_types=1);

namespace App\Bonus\UseCase;

use App\Bonus\Factory\BonusFactory;
use App\Bonus\Repository\BonusRepositoryInterface;
use App\Flusher;

readonly class AddHandler
{
    public function __construct(
        private BonusRepositoryInterface $bonusRepository,
        private BonusFactory $bonusFactory,
        private Flusher $flusher
    ) {
    }

    public function handle(AddCommand $command): void
    {
         $bonus = $this->bonusFactory->create($command);
         $this->bonusRepository->add($bonus);
         $this->flusher->flush();
    }
}

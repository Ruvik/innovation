<?php

declare(strict_types=1);

namespace App\Bonus\Test\Unit;

use App\Bonus\Entity\Bonus;
use App\Bonus\Entity\Type;
use App\Bonus\Factory\BonusFactory;
use App\Bonus\Repository\BonusRepositoryInterface;
use App\Bonus\UseCase\AddCommand;
use App\Bonus\UseCase\AddHandler;
use App\Flusher;
use PHPUnit\Framework\TestCase;

class AddHandlerTest extends TestCase
{
    public function testHandle()
    {
        $rewardType = Type::SMILE;
        $bonusName = 'Test Bonus';

        $command = new AddCommand($bonusName, $rewardType);

        $bonus = $this->createMock(Bonus::class);

        $bonusFactory = $this->createMock(BonusFactory::class);
        $bonusFactory->expects($this->once())
            ->method('create')
            ->with($command)
            ->willReturn($bonus);

        $bonusRepository = $this->createMock(BonusRepositoryInterface::class);
        $bonusRepository->expects($this->once())
            ->method('add')
            ->with($bonus);

        $flusher = $this->createMock(Flusher::class);
        $flusher->expects($this->once())
            ->method('flush');

        $handler = new AddHandler($bonusRepository, $bonusFactory, $flusher);

        $handler->handle($command);
    }
}

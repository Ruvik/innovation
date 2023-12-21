<?php

declare(strict_types=1);

namespace App\ClientReward\Test\Unit;

use App\Bonus\BonusService;
use App\Bonus\Entity\Type;
use App\Client\ClientService;
use App\ClientReward\ClientRewardService;
use App\ClientReward\UseCase\ApplyRewardCommand;
use App\ClientReward\UseCase\ApplyRewardHandler;
use App\Flusher;
use PHPUnit\Framework\TestCase;

class ApplyRewardHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        // Create mocks
        $clientService = $this->createMock(ClientService::class);
        $bonusService = $this->createMock(BonusService::class);
        $clientRewardService = $this->createMock(ClientRewardService::class);
        $flusher = $this->createMock(Flusher::class);

        $fakeBonusId = \App\Bonus\Entity\Id::generate();
        $bonusService->method('getIdsByType')->willReturn([$fakeBonusId]);
        $clientRewardService->method('add');


        $handler = new ApplyRewardHandler($bonusService, $clientRewardService, $flusher);


        $command = new ApplyRewardCommand(\App\Client\Entity\Id::generate(), Type::SMILE);


        $result = $handler->handle($command);

        $this->assertIsArray($result);
        $this->assertContains($fakeBonusId->getValue(), $result);
    }
}

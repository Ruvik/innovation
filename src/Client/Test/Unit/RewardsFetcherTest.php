<?php

declare(strict_types=1);

namespace App\Client\Test\Unit;

use App\Bonus\BonusService;
use App\Client\Entity\Id as ClientId;
use App\Client\UseCase\RewardsFetcher;
use App\Client\UseCase\RewardsQuery;
use App\ClientReward\ClientRewardService;
use App\ClientReward\Entity\ClientReward;
use App\ClientReward\Entity\Id;
use PHPUnit\Framework\TestCase;

class RewardsFetcherTest extends TestCase
{
    public function testFetch(): void
    {
        $clientRewardService = $this->createMock(ClientRewardService::class);
        $bonusService = $this->createMock(BonusService::class);

        $fakeRewards = [new ClientReward(Id::generate(), ClientId::generate(), \App\Bonus\Entity\Id::generate())];
        $clientRewardService->method('getRewardsByClientId')->willReturn($fakeRewards);
        $clientRewardService->method('getCountRewardsByClientId')->willReturn(1);


        $fetcher = new RewardsFetcher($clientRewardService, $bonusService);

        $query = new RewardsQuery(ClientId::generate(), 1, 1);

        $result = $fetcher->fetch($query);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('meta', $result);
    }
}

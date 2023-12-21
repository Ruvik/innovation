<?php

declare(strict_types=1);

namespace App\Bonus\Test\Unit;

use App\Bonus\Entity\Hug;
use App\Bonus\Entity\Type;
use App\Bonus\Entity\Smile;
use App\Bonus\Factory\BonusFactory;
use App\Bonus\UseCase\AddCommand;
use PHPUnit\Framework\TestCase;

class BonusFactoryTest extends TestCase
{
    public function testCreateSmileBonus(): void
    {
        $factory = new BonusFactory();
        $command = new AddCommand('Test Smile Bonus', Type::SMILE);

        $bonus = $factory->create($command);

        $this->assertInstanceOf(Smile::class, $bonus);
        $this->assertSame('Test Smile Bonus', $bonus->getName());
    }

    public function testCreateHugBonus(): void
    {
        $factory = new BonusFactory();
        $command = new AddCommand('Test Hug Bonus', Type::HUG);

        $bonus = $factory->create($command);

        $this->assertInstanceOf(Hug::class, $bonus);
        $this->assertSame('Test Hug Bonus', $bonus->getName());
    }
}

<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Bonus\Entity\Hug;
use App\Bonus\Entity\Id;
use App\Bonus\Entity\Smile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @psalm-suppress UnusedClass
 */
class BonusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $bonusSmile = new Smile(Id::generate(), 'Test smile bonus - ' . $i);
            $manager->persist($bonusSmile);

            $bonusHug = new Hug(Id::generate(), 'Test hug bonus - ' . $i);
            $manager->persist($bonusHug);
        }

        $manager->flush();
    }
}

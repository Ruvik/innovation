<?php

declare(strict_types=1);

namespace App\ClientReward\EventListener;

use App\Bonus\Entity\Type;
use App\ClientReward\UseCase\ApplyRewardCommand;
use App\ClientReward\UseCase\ApplyRewardHandler;
use App\Event\Client\BirthdayEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @psalm-suppress UnusedClass
 */
readonly class BirthdayEventListener
{
    public function __construct(private ApplyRewardHandler $applyRewardHandler)
    {
    }

    #[AsMessageHandler]
    public function onBirthday(BirthdayEvent $event): array
    {
        return $this->applyRewardHandler->handle(new ApplyRewardCommand(
            $event->clientId,
            Type::HUG
        ));
    }
}

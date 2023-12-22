<?php

declare(strict_types=1);

namespace App\Client\UseCase;

use App\Client\ClientService;
use App\Event\Client\BirthdayEvent;
use App\Event\Client\EmailVerifiedEvent;
use App\Flusher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class UpdateHandler
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private ClientService $clientService,
        private EntityManagerInterface $entityManager,
        private Flusher $flusher,
        private MessageBusInterface $messageBus
    ) {
    }

    public function handle(UpdateCommand $command): array
    {
        $client = $this->clientService->get($command->id);
        $this->entityManager->beginTransaction();
        try {
            $appliedIds = [];
            if ($command->emailVerified && !$client->isEmailVerified()) {
                $client->markEmailVerified();

                $envelope = $this->messageBus->dispatch(new EmailVerifiedEvent($client->getId()));

                $handledStamp = $envelope->last(\Symfony\Component\Messenger\Stamp\HandledStamp::class);
                $appliedIds = $appliedIds + $handledStamp->getResult();
            }

            if ($command->isBirthday && !$client->isBirthday()) {
                $client->markIsBirthday();
                $envelope = $this->messageBus->dispatch(new BirthdayEvent($client->getId()));

                $handledStamp = $envelope->last(\Symfony\Component\Messenger\Stamp\HandledStamp::class);
                $appliedIds = $appliedIds + $handledStamp->getResult();
            }
            $this->flusher->flush();
            $this->entityManager->commit();
            return [
                'id' => $client->getId()->getValue(),
                'appliedIds' => $appliedIds,
            ];
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}

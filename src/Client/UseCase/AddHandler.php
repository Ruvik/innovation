<?php

declare(strict_types=1);

namespace App\Client\UseCase;

use App\Client\ClientService;
use App\Client\Entity\Client;
use App\Client\Entity\Id;
use App\Event\Client\BirthdayEvent;
use App\Event\Client\EmailVerifiedEvent;
use App\Flusher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: "AddHandlerResponse", description: "Response returned by AddHandler", type: "object")]
readonly class AddHandler
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

    public function handle(AddCommand $command): array
    {
        $client = new Client(Id::generate());
        $this->entityManager->beginTransaction();
        try {
            $this->clientService->add($client);
            $appliedIds = [];
            if ($command->emailVerified) {
                $client->markEmailVerified();

                $envelope = $this->messageBus->dispatch(new EmailVerifiedEvent($client->getId()));

                $handledStamp = $envelope->last(\Symfony\Component\Messenger\Stamp\HandledStamp::class);
                $appliedIds = $appliedIds + $handledStamp->getResult();
            }

            if ($command->isBirthday) {
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

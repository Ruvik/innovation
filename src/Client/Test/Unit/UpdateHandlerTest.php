<?php

declare(strict_types=1);

namespace App\Client\Test\Unit;

use App\Client\ClientService;
use App\Client\Entity\Client;
use App\Client\Entity\Id;
use App\Client\UseCase\UpdateCommand;
use App\Client\UseCase\UpdateHandler;
use App\Flusher;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class UpdateHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $clientService = $this->createMock(ClientService::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $flusher = $this->createMock(Flusher::class);
        $messageBus = $this->createMock(MessageBusInterface::class);

        $id = Id::generate();
        $client = new Client($id);
        $clientService->method('get')->willReturn($client);
        $messageBus->method('dispatch')
            ->willReturn(new Envelope(new \stdClass(), [new HandledStamp(['test' => 'result'], 'handler')]));

        $handler = new UpdateHandler($clientService, $entityManager, $flusher, $messageBus);

        $command = new UpdateCommand($id, true, true);
        $result = $handler->handle($command);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('appliedIds', $result);
    }
}

<?php

declare(strict_types=1);

namespace App\Client\Test\Unit;

use App\Client\ClientService;
use App\Client\UseCase\AddCommand;
use App\Client\UseCase\AddHandler;
use App\Flusher;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class AddHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $clientService = $this->createMock(ClientService::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $flusher = $this->createMock(Flusher::class);
        $messageBus = $this->createMock(MessageBusInterface::class);

        $entityManager->method('beginTransaction');
        $entityManager->method('commit');
        $envelope = new Envelope(new \stdClass(), [new HandledStamp(['test' => 'result'], 'handler')]);

        $messageBus->expects(new InvokedCountMatcher(2))->method('dispatch')->willReturn($envelope);

        $handler = new AddHandler($clientService, $entityManager, $flusher, $messageBus);

        $command = new AddCommand(true, true);

        $result = $handler->handle($command);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('appliedIds', $result);
    }

    public function testWithoutMessageBusHandle()
    {
        $clientService = $this->createMock(ClientService::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $flusher = $this->createMock(Flusher::class);
        $messageBus = $this->createMock(MessageBusInterface::class);

        $messageBus->method('dispatch')
            ->willReturn(new Envelope(new \stdClass(), [new HandledStamp(['test' => 'result'], 'handler')]));

        $messageBus->expects($this->never())->method('dispatch');
        $handler = new AddHandler($clientService, $entityManager, $flusher, $messageBus);

        $command = new AddCommand(false, false);
        $handler->handle($command);
    }
}

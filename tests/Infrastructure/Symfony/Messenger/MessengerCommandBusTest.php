<?php

declare(strict_types=1);

namespace Botilka\Tests\Infrastructure\Symfony\Messenger;

use Botilka\Application\Command\CommandResponse;
use Botilka\Infrastructure\Symfony\Messenger\MessengerCommandBus;
use Botilka\Tests\Fixtures\Application\Command\SimpleCommand;
use Botilka\Tests\Fixtures\Domain\StubEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessengerCommandBusTest extends TestCase
{
    public function testDispatch(): void
    {
        $command = new SimpleCommand('foo', 132);
        $commandResponse = new CommandResponse('foo', new StubEvent(123));
        $stamp = new HandledStamp($commandResponse, 'foo');

        $messengerBus = $this->createMock(MessageBusInterface::class);
        $messengerBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn(new Envelope(new \stdClass(), $stamp));

        $bus = new MessengerCommandBus($messengerBus);
        $this->assertSame($commandResponse, $bus->dispatch($command));
    }
}

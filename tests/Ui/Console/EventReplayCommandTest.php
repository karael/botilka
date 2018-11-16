<?php

declare(strict_types=1);

namespace Botilka\Tests\Ui\Console;

use Botilka\Event\EventReplayer;
use Botilka\Ui\Console\EventReplayCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

final class EventReplayCommandTest extends KernelTestCase
{
    /** @dataProvider executeProvider */
    public function testExecute(string $id, ?int $from, ?int $to): void
    {
        $replayer = $this->createMock(EventReplayer::class);
        $command = new EventReplayCommand($replayer);

        $this->assertSame('botilka:event_store:replay', $command->getName());

        $replayer->expects($this->once())
            ->method('replay')
            ->with(...[$id, $from, $to]);

        $input = new ArrayInput(['id' => $id, '--from' => $from, '--to' => $to]);
        $command->run($input, new BufferedOutput());
    }

    public function executeProvider(): array
    {
        return [
            ['foo', null, null],
            ['bar', 1, null],
            ['baz', 1, 10],
        ];
    }
}
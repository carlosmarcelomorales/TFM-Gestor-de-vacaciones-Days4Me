<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Infrastructure\CommandBus;

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

final class TacticianHandlerInflector implements MethodNameInflector
{
    public function inflect($command, $commandHandler): string
    {
        return '__invoke';
    }


}
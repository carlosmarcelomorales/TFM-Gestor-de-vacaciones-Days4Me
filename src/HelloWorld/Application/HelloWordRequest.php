<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\HelloWorld\Application;

final class HelloWordRequest
{

    private string $helloWorld;

    public function __construct(?string $helloWorld = null)
    {
        $this->helloWorld = $helloWorld ?? 'Hello World desde command';
    }

    public function helloWorld(): string
    {
        return $this->helloWorld;
    }
}

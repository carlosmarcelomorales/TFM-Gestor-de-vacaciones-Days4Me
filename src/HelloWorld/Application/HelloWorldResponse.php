<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\HelloWorld\Application;


use JsonSerializable;

final class HelloWorldResponse implements JsonSerializable
{
    private string $helloWorld;

    public function __construct(string $helloWorld)
    {
        $this->helloWorld = $helloWorld;
    }

    public function helloWorld()
    {
        return $this->helloWorld;
    }

    public function jsonSerialize()
    {
        return
            [
                'helloWorld' => $this->helloWorld()
            ];
    }
}
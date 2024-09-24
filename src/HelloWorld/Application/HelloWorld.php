<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\HelloWorld\Application;

final class HelloWorld
{

    public function __invoke(HelloWordRequest $helloWorld): HelloWorldResponse
    {
        return new HelloWorldResponse($helloWorld->helloWorld());
    }
}
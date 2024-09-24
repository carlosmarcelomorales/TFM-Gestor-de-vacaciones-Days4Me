<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\HelloWorld;

use Exception;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use TFM\HolidaysManagement\HelloWorld\Application\HelloWordRequest;

final class GetHelloWorldController extends AbstractController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(): JsonResponse
    {
        try {
            $response = $this->commandBus->handle(new HelloWordRequest());
            return new JsonResponse(
                [$response],
                200,
                ['Access-Control-Allow-Origin' => '*']
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                $exception->getMessage(),
                200
            );
        }
    }
}

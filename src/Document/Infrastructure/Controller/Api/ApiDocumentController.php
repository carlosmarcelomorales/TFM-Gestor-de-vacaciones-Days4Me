<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Infrastructure\Controller\Api;

use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use TFM\HolidaysManagement\Document\Application\Api\GetApiDocumentsRequest;

final class ApiDocumentController extends AbstractController
{

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("/"), name="api_get_documents", methods={"GET"})
     * @return JsonResponse
     */

    public function documents(): JsonResponse
    {
        $documents = $this->commandBus->handle(
            new GetApiDocumentsRequest(
                []
            )
        );
        return new JsonResponse($documents);
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("{id}", name="api_get_one_document", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $documents = $this->commandBus->handle(
            new GetApiDocumentsRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($documents);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("/name/{name}", name="api_get_document_by_name", methods={"GET"})
     * @param $name
     * @return JsonResponse
     */
    public function getByWorkPlace($name): JsonResponse
    {
        $documents = $this->commandBus->handle(
            new GetApiDocumentsRequest(
                [
                    'name' => $name,
                ]
            )
        );
        return new JsonResponse($documents);
    }

}
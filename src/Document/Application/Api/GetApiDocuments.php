<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Application\Api;


use TFM\HolidaysManagement\Document\Domain\DocumentRepository;

final class GetApiDocuments
{
    private DocumentRepository $repository;

    public function __construct(DocumentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiDocumentsRequest $request): array
    {
        $documents = $this->repository->allFiltered($request->filters());
        return DocumentResponse::fromArray($documents);
    }
}
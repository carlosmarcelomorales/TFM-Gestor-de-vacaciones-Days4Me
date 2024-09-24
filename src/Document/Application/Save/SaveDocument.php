<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Application\Save;


use DateTimeImmutable;
use TFM\HolidaysManagement\Document\Domain\DocumentRepository;
use TFM\HolidaysManagement\Document\Domain\Model\Aggregate\Document;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class SaveDocument
{
    private DocumentRepository $repository;

    public function __construct(DocumentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SaveDocumentRequest $saveDocumentRequest): Document
    {
        $document = Document::create(
            new IdentUuid(),
            $saveDocumentRequest->name(),
            $saveDocumentRequest->link(),
            new DateTimeImmutable(),
            null,
            null
        );

        $this->repository->save($document);

        return  $document;
    }
}

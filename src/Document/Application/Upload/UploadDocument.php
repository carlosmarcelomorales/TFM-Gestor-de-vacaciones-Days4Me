<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Application\Upload;


use TFM\HolidaysManagement\Document\Domain\DocumentRepository;


final class UploadDocument
{
    private DocumentRepository $repository;

    public function __construct(DocumentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UploadDocumentRequest $documentsRequest): void
    {
        $this->repository->upload(
            $documentsRequest->imagePathUploaded(),
            $documentsRequest->nameUploadedImage()
        );
    }
}

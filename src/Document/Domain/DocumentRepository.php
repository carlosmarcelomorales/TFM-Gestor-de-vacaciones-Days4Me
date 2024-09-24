<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Domain;


use TFM\HolidaysManagement\Document\Domain\Model\Aggregate\Document;

interface DocumentRepository
{
    public function upload(string $documentPathUpload, string $documentPathNew): void;

    public function save(Document $document): void;
}
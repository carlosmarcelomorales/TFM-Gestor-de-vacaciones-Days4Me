<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Application\Api;

use JsonSerializable;
use TFM\HolidaysManagement\Document\Domain\Model\Aggregate\Document;

final class DocumentResponse implements JsonSerializable
{
    private string $id;
    private string $name;
    private string $link;

    private function __construct(Document $document)
    {
        $this->id = $document->id()->value();
        $this->name = $document->name();
        $this->link = $document->link();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function link(): string
    {
        return $this->link;
    }

    public static function fromArray(array $documents): array
    {
        $documentArray = [];

        foreach ($documents as $document) {
            $documentArray[] = new self($document);
        }

        return $documentArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'link' => $this->link(),
        ];
    }
}

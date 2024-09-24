<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Application\Save;


final class SaveDocumentRequest
{
    private string $name;
    private ?string $link;

    public function __construct(
        string $name,
        ?string $link
    ) {
        $this->name = $name;
        $this->link = $link;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function link(): ?string
    {
        return $this->link;
    }
}

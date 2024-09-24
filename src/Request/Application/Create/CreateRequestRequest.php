<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Create;

use Closure;
use DateTimeImmutable;
use TFM\HolidaysManagement\Document\Application\Upload\UploadDocumentRequest;
use function Lambdish\Phunctional\map;

final class CreateRequestRequest
{
    private ?string $description;
    private string $user;
    private string $typesRequest;
    private ?string $statusRequest;
    private array $uploadDocumentRequest;
    private DateTimeImmutable $requestPeriodStart;
    private DateTimeImmutable $requestPeriodEnd;

    public function __construct(
        ?string $description,
        string $user,
        string $typesRequest,
        ?string $statusRequest,
        DateTimeImmutable $requestPeriodStart,
        DateTimeImmutable $requestPeriodEnd,
        UploadDocumentRequest ...$uploadDocumentRequest
    ) {
        $this->description = $description;
        $this->user = $user;
        $this->typesRequest = $typesRequest;
        $this->statusRequest = $statusRequest;
        $this->uploadDocumentRequest = $uploadDocumentRequest;
        $this->requestPeriodStart = $requestPeriodStart;
        $this->requestPeriodEnd = $requestPeriodEnd;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function user(): string
    {
        return $this->user;
    }

    public function typesRequest(): string
    {
        return $this->typesRequest;
    }

    public function statusRequest(): ?string
    {
        return $this->statusRequest;
    }

    public function uploadDocumentRequest(): array
    {
        return $this->uploadDocumentRequest;
    }

    public function requestPeriodStart(): DateTimeImmutable
    {
        return $this->requestPeriodStart;
    }

    public function requestPeriodEnd(): DateTimeImmutable
    {
        return $this->requestPeriodEnd;
    }

    public function toPrimitives(): array
    {
        return map($this->toPrimitiveDocumentUpload(), $this->uploadDocumentRequest);
    }

    private function toPrimitiveDocumentUpload(): Closure
    {
        return fn(UploadDocumentRequest $uploadRequest) => [
            'imagePathUploaded' => $uploadRequest->imagePathUploaded(),
            'imageExtension' => $uploadRequest->imageExtension(),
            'nameUploadedImage' => $uploadRequest->nameUploadedImage()
        ];
    }
}

<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Domain\Model\ValueObject;


use InvalidArgumentException;
use TFM\HolidaysManagement\Shared\Infrastructure\Utils\File;
use TFM\HolidaysManagement\Shared\Infrastructure\Utils\Slugger;

final class newDocument
{
    public const DOCUMENT_UPLOADS_PATH = '/app/public/repository';
    private string $imagePathUploaded;
    private string $imageExtension;
    private string $newImageName;
    private string $nameUploadedImage;

    public function __construct(string $imagePathUploaded, string $imageExtension, $nameUploadedImage)
    {
        $this->imageExtension = $imageExtension;
        $this->nameUploadedImage = $nameUploadedImage;

        $this->IsImage($imagePathUploaded);
        $this->createImageName();

        $this->imagePathUploaded = $imagePathUploaded;
    }

    public function imagePathUploaded(): string
    {
        return $this->imagePathUploaded;
    }

    public function imageUploadsPath(): string
    {
        return File::checkDirectory(self::DOCUMENT_UPLOADS_PATH);
    }

    public function imagePathNew(): string
    {
        return $this->imageUploadsPath() . '/' . $this->newImageName;
    }

    public function imageExtension(): string
    {
        return $this->imageExtension;
    }

    public function newImageName(): string
    {
        return $this->newImageName;
    }

    private function createImageName(): void
    {
        $this->newImageName = Slugger::slugify($this->nameUploadedImage) . '-' . uniqid() . "." . $this->imageExtension();
    }

    private function isImage($image): void
    {
       // if (false === File::checkIsDocument($image)) {
        //    throw new InvalidArgumentException('The file is not a valid image');
        //}
    }
}

<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Application\Upload;



final class UploadDocumentRequest
{
    private string $imagePathUploaded;
    private string $imageExtension;
    private string $nameUploadedImage;

    public function __construct(string $imagePathUploaded, string $imageExtension, string $nameUploadedImage)
    {
        $this->imagePathUploaded = $imagePathUploaded;
        $this->imageExtension = $imageExtension;
        $this->nameUploadedImage = $nameUploadedImage;
    }

    public function imagePathUploaded(): string
    {
        return $this->imagePathUploaded;
    }

    public function imageExtension(): string
    {
        return $this->imageExtension;
    }

    public function nameUploadedImage(): string
    {
        return $this->nameUploadedImage;
    }
}

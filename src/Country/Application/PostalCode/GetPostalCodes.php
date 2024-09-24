<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\PostalCode;

use TFM\HolidaysManagement\Country\Domain\PostalCodeRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetPostalCodes
{
    private PostalCodeRepository $postalCodeRepository;

    public function __construct(PostalCodeRepository $postalCodeRepository)
    {
        $this->postalCodeRepository = $postalCodeRepository;
    }

    public function __invoke(GetPostalCodesRequest $request)
    {
        $postalCodes = $this->postalCodeRepository->ofTownId(new IdentUuid($request->townId()));

        return PostalCodeResponse::fromArray($postalCodes);
    }
}

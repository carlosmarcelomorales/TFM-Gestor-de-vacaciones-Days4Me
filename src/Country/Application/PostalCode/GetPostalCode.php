<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\PostalCode;

use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Country\Domain\PostalCodeRepository;

final class GetPostalCode
{
    private PostalCodeRepository $postalCodeRepository;

    public function __construct(PostalCodeRepository $postalCodeRepository)
    {
        $this->postalCodeRepository = $postalCodeRepository;;
    }

    public function __invoke(GetPostalCodeRequest $findPostalCodeRequest): PostalCode
    {
        return $this->postalCodeRepository->ofValueOrDefault($findPostalCodeRequest->value());
    }


}

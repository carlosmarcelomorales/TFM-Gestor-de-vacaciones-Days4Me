<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Country;

use TFM\HolidaysManagement\Country\Domain\CountryRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetCountry
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function __invoke($request): CountryResponse
    {
        $country = $this->countryRepository->ofId(new IdentUuid($request->id()));

        return CountryResponse::fromCountry($country);
    }
}

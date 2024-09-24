<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Country;

use TFM\HolidaysManagement\Country\Domain\CountryRepository;

final class GetCountries
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function __invoke(GetCountriesRequest $request): array
    {
        $countries = $this->countryRepository->allSorted($request->orderBy());

        return CountryResponse::fromArray($countries);
    }
}

<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Infrastructure\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TFM\HolidaysManagement\Country\Application\Country\GetCountriesRequest;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodesRequest;
use TFM\HolidaysManagement\Country\Application\Region\GetRegionsRequest;
use TFM\HolidaysManagement\Country\Application\Town\Search\GetTownsRequest;
use TFM\HolidaysManagement\Country\Application\Town\ViewTownsRequest;
use TFM\HolidaysManagement\Shared\Infrastructure\Utils\Formatter;

final class CountryController extends AbstractController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function countries(): JsonResponse
    {
        if (empty($this->getUser())) return $this->render('partials/404_error.html.twig', []);

        $countries = $this->commandBus->handle(
            new GetCountriesRequest(
                [
                    'name' => 'ASC',
                ]
            )
        );
        return new JsonResponse($countries);
    }

    public function regions(Request $request): JsonResponse
    {
        if (empty($this->getUser())) return $this->render('partials/404_error.html.twig', []);

        $regions = [];

        $countryId = $request->get('country_id');

        if (!empty($countryId) ) {
            $regions = $this->commandBus->handle(
                new GetRegionsRequest(
                    [
                        'country_id' => $countryId,
                    ],
                )
            );
        }

        $html = $this->createOptionsSelectHtml(Formatter::formatterKeyValueCountriesForms($regions));

        return new JsonResponse($html);
    }


    public function towns(Request $request): JsonResponse
    {
        if (empty($this->getUser())) return $this->render('partials/404_error.html.twig', []);

        $regionId = $request->get('region_id');
        $towns = [];

        if (!empty($regionId)) {
            $towns = $this->commandBus->handle(
                new GetTownsRequest(
                    [
                        'region_id' => $regionId,
                    ]
                )
            );

        }

        $html = $this->createOptionsSelectHtml(Formatter::formatterKeyValueCountriesForms($towns));

        return new JsonResponse($html);
    }

    public function postalCodes(Request $request): JsonResponse
    {
        if (empty($this->getUser())) return $this->render('partials/404_error.html.twig', []);

        $townId = $request->get('town_id');

        $postalCodes = [];

        if (!empty($townId)) {
            $postalCodes = $this->commandBus->handle(
                new GetPostalCodesRequest(
                    $townId
                )
            );
        }

        $html = $this->createOptionsSelectHtml(Formatter::formatterKeyValueCountriesForms($postalCodes));

        return new JsonResponse($html);
    }

    private function createOptionsSelectHtml (array $array) : string
    {
        $html = "";

        foreach($array as $key => $value){
            $html .= "<option value=". $value .">". $key . "</option>";
        }
        return $html;
    }
}

<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\Company\Infrastructure\Controller\Api;


use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Company\Application\Create\CreateCompanyRequest;
use TFM\HolidaysManagement\Company\Application\Api\CompanyResponse;
use TFM\HolidaysManagement\Company\Application\Api\GetApiCompaniesRequest;
use TFM\HolidaysManagement\Company\Application\Update\UpdateCompanyRequest;

final class ApiCompanyController extends AbstractController
{

    private CommandBus $commandBus;
    private TranslatorInterface $translator;

    public function __construct(CommandBus $commandBus, TranslatorInterface $translator)
    {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/"), name="api_get_companies", methods={"GET"})
     * @return JsonResponse
     */
    public function companies(): JsonResponse
    {
        $companies = $this->commandBus->handle(
            new GetApiCompaniesRequest(
                []
            )
        );
        return new JsonResponse($companies);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("{id}", name="api_get_one_company", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function byId(string $id): JsonResponse
    {
        $companies = $this->commandBus->handle(
            new GetApiCompaniesRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($companies);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("company/{vat}", name="api_get_one_company_by_vat", methods={"GET"})
     * @param $vat
     * @return JsonResponse
     */
    public function byVat($vat): JsonResponse
    {
        $companies = $this->commandBus->handle(
            new GetApiCompaniesRequest(
                [
                    'vat' => $vat,
                ]
            )
        );
        return new JsonResponse($companies);
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("set", name="api_set_company", methods={"POST"})
     * @param $request
     * @return JsonResponse
     */
    public function set(Request $request): JsonResponse
    {

        try {
            $company = $this->commandBus->handle(new CreateCompanyRequest(
                    $request->get('vat'),
                    $request->get('name'),
                    $request->get('description'),
                    $request->get('web_site'),
                    $request->get('phone_number1'),
                    $request->get('phone_number2'),
                    $request->get('email'),
                    $request->get('street_name'),
                    intval($request->get('number')),
                    $request->get('floor'),
                    $request->get('postal_code'),
                    $request->get('blocked') === 'true' ? true : false,
                    0
                )
            );

        } catch
        (Exception $e) {

            throw new InvalidArgumentException(sprintf($this->translator->trans('company.controller.save.form.error'),
                $e->getMessage()));
        }

        return new JsonResponse(CompanyResponse::fromCompany($company));
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("update/{id}", name="api_update_company", methods={"PUT"})
     * @param $request
     * @return JsonResponse
     */
    public function update(string $id, Request $request): JsonResponse
    {

        try {
            $company = $this->commandBus->handle(new UpdateCompanyRequest(
                $id,
                $request->get('vat'),
                $request->get('name'),
                $request->get('description'),
                $request->get('web_site'),
                $request->get('phone_number1'),
                $request->get('phone_number2'),
                $request->get('email'),
                $request->get('street_name'),
                intval($request->get('number')),
                $request->get('floor'),
                $request->get('postal_code'),
                $request->get('blocked') === 'true' ? true : false,
                0
            ));
        } catch (Exception $e) {
            throw new InvalidArgumentException(sprintf('Error to upload image. Error:%s', $e->getMessage()));
        }

        return new JsonResponse(CompanyResponse::fromCompany($company));

    }
}

<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Infrastructure\Controller;

use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Company\Application\Create\CreateCompanyRequest;
use TFM\HolidaysManagement\Company\Application\Get\GetCompaniesRequest;
use TFM\HolidaysManagement\Company\Application\Get\GetCompanyRequest;
use TFM\HolidaysManagement\Company\Application\Update\UpdateCompanyRequest;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyIdNotExistsException;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Company\Infrastructure\Form\CompanyType;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;


final class CompanyController extends AbstractController
{
    private CommandBus $commandBus;
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $urlGenerator;
    private array $filters;

    public function __construct(
        CommandBus $commandBus,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(): Response
    {
        $this->init();
        $companies = $this->commandBus->handle(new GetCompaniesRequest($this->filters));
        return $this->render('Company/index.html.twig', ['companies' => $companies]);
    }

    private function companyForm(Company $company = null, string $route): FormInterface
    {

        return $this->createForm(
            CompanyType::class,
            $company,
            [
                'method' => 'POST',
                'action' => $this->generateUrl($route),
            ]
        );
    }

    public function add(): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->companyForm(null, 'save_company');
        return $this->render('Company/company.html.twig', ['Form' => $form->createView()]);
    }

    public function save(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->companyForm(null, 'save_company');
        $form->handleRequest($request);

        $postalCode = $request->get('postal_code') ? $request->get('postal_code') : PostalCode::DEFAULT_NOT_POSTAL_CODE;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(new CreateCompanyRequest(
                        $form->getData()['vat'],
                        $form->getData()['name'],
                        $form->getData()['description'],
                        $form->getData()['web_site'],
                        $form->getData()['phone_number1'],
                        $form->getData()['phone_number2'],
                        $form->getData()['email'],
                        $form->getData()['street_name'],
                        0,
                        $form->getData()['floor'],
                        $postalCode,
                        false,
                        false
                    )
                );
                $this->get('session')->getFlashBag()->add('ok', $this->translator->trans('messages.save.ok'));

            } catch
            (Exception $e) {

                throw new InvalidArgumentException(sprintf($this->translator->trans('company.controller.save.form.error'),
                    $e->getMessage()));
            }
        }
        return $this->render('Company/company.html.twig', ['Form' => $form->createView()]);
    }

    public function edit(string $companyId): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        try {
            $company = $this->commandBus->handle(new GetCompanyRequest(
                $companyId
            ));
        } catch (Exception $e) {
            throw new CompanyIdNotExistsException($companyId);
        }

        if (false === $company->isUpdated()) {
            $this->get('session')->getFlashBag()->add('error',
                $this->translator->trans('the.company.data.not.complete'));
        }

        $form = $this->companyForm($company, 'update_company');

        $positionInfo = [
            'postalCode' => $this->getUser()->postalCodes()->value(),
            'town'       => $this->getUser()->postalCodes()->towns()[0]->name(),
            'region'    => $this->getUser()->postalCodes()->towns()[0]->regions()->name(),
            'country'    => $this->getUser()->postalCodes()->towns()[0]->regions()->countries()->name()
        ];

        return $this->render('Company/company.html.twig', [
            'Form'          => $form->createView(),
            'company'       => $company,
            'positionInfo'  => $positionInfo
        ]);
    }

    public function update(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->companyForm(null, 'update_company');
        $form->handleRequest($request);

        $postalCode = $request->get('postal_code') ? $request->get('postal_code') : PostalCode::DEFAULT_NOT_POSTAL_CODE;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(new UpdateCompanyRequest(
                        $form->getData()['id'],
                        $form->getData()['vat'],
                        $form->getData()['name'],
                        $form->getData()['description'],
                        $form->getData()['web_site'],
                        $form->getData()['phone_number1'],
                        $form->getData()['phone_number2'],
                        $form->getData()['email'],
                        $form->getData()['street_name'],
                        intval($form->getData()['number']),
                        $form->getData()['floor'],
                        $postalCode,
                        $form->getData()['blocked'] ? $form->getData()['blocked'] : false,
                        $form->getData()['business_days'] ? $form->getData()['business_days'] : false
                    )
                );
                $this->get('session')->getFlashBag()->add('ok', $this->translator->trans('messages.save.ok'));

            } catch (Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', 'll');
                throw new InvalidArgumentException(sprintf($this->translator->trans('company.controller.update.form.error'), $e->getMessage()));
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('list_company'));

    }

    private function init()
    {
        $user = $this->getUser();
        $this->filters = [];

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $this->filters = ['id' => $user->companies()->id()->value()];
        }
    }
}

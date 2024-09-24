<?php

namespace TFM\HolidaysManagement\Company\Infrastructure\Form;

use League\Tactician\CommandBus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use TFM\HolidaysManagement\Company\Application\Create\CreateCompanyRequest;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Application\Country\CountryResponse;
use TFM\HolidaysManagement\Country\Application\Country\GetCountriesRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TypeError;

final class CompanyType extends AbstractType implements DataMapperInterface
{
    private CommandBus $commandBus;
    private Security  $security;

    public function __construct(CommandBus $commandBus, Security $security)
    {
        $this->commandBus = $commandBus;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();
        $countryUser = $user->PostalCodes()->towns()[0]->Regions()->Countries()->id();

        if (null !== $builder->getData()) {

            $countryUser = $builder->getData()->postalCodes()->towns()[0]->Regions()->Countries()->id();

        }

        $countries = $this->commandBus->handle(
            new GetCountriesRequest()
        );

        $builder
            ->add('submit', SubmitType::class)
            ->add('id', HiddenType::class, [
            ])
            ->add('vat', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Regex(
                        [
                            'pattern' => Company::REGEX_VAT,
                            'message' => 'The vat is incorrectly',
                        ]
                    ),
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_VAT,
                        'minMessage' => 'The NIF must have at least {{ limit }} characters',
                        'maxMessage' => 'The NIF must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field name cannot be empty',
                        ]
                    ),
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_NAME,
                        'minMessage' => 'The name must have at least {{ limit }} characters',
                        'maxMessage' => 'The name must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_DESCRIPTION,
                        'minMessage' => 'The description must have at least {{ limit }} characters',
                        'maxMessage' => 'The description must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field email cannot be empty!',
                        ]
                    ),
                    new Email(
                        [
                            'message' => 'Your email doesnt seems to be valid'
                        ]
                    ),
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_EMAIL,
                        'minMessage' => 'The email address must have at least {{ limit }} characters',
                        'maxMessage' => 'The email address must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('web_site', UrlType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_WEBSITE,
                        'minMessage' => 'The Web site must have at least {{ limit }} characters',
                        'maxMessage' => 'The Web site must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('phone_number1', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'validate',
                    'id' => 'icon_telephone',
                    'placeholder' => '+34655102030 ó 0034655102030',
                ],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_PHONE,
                        'minMessage' => 'The phone number must have at least {{ limit }} characters',
                        'maxMessage' => 'The phone number must have at most {{ limit }} characters',
                    ]),
                    new Regex(
                        [
                            'pattern' => User::REGEX_PHONE,
                            'message' => 'the field phone number is not valid',
                        ]
                    ),
                ],
            ])
            ->add('phone_number2', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_PHONE,
                        'minMessage' => 'The phone number must have at least {{ limit }} characters',
                        'maxMessage' => 'The phone number must have at most {{ limit }} characters',
                    ]),
                    new Regex(
                        [
                            'pattern' => User::REGEX_PHONE,
                            'message' => 'the field phone number is not valid',
                        ]
                    ),
                ],
                'attr' => [
                    'placeholder' => '+34655102030 ó 0034655102030'
                ]
            ])
            ->add('street_name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_PHONE,
                        'minMessage' => 'The street name must have at least {{ limit }} characters',
                        'maxMessage' => 'The street name must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('number', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_PHONE,
                        'minMessage' => 'The number street must have at least {{ limit }} characters',
                        'maxMessage' => 'The number street must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('floor', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_PHONE,
                        'minMessage' => 'The floor must have at least {{ limit }} characters',
                        'maxMessage' => 'The floor must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices' => $countries,
                'choice_value' => function ($country) {
                    if ($country instanceof CountryResponse) {
                        return $country->id();
                    }

                    return $country;
                },
                'choice_label' => function ($country, $key, $index) {
                    return $country->name();
                },
                'data' => $countryUser,
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('business_days', CheckboxType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-check-input',
                        'autocomplete' => 'off'
                    ]
                    ,
                    'label_attr' => [
                        'class' => 'form-check-label'
                    ]
                ]
            );
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('blocked', CheckboxType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-check-input',
                        'autocomplete' => 'off'
                    ]
                    ,
                    'label_attr' => [
                        'class' => 'form-check-label'
                    ]
                ]
            );
        }

    }

    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof Company) {
            throw new UnexpectedTypeException($viewData, Company::class);
        }

        $forms = iterator_to_array($forms);
        try {
            $forms['vat']->setData($viewData->vat());
            $forms['name']->setData($viewData->name());
            $forms['description']->setData($viewData->description());
            $forms['web_site']->setData($viewData->webSite());
            $forms['phone_number1']->setData($viewData->phoneNumber1());
            $forms['phone_number2']->setData($viewData->phoneNumber2());
            $forms['email']->setData($viewData->email());
            $forms['street_name']->setData($viewData->streetName());
            $forms['number']->setData($viewData->number());
            $forms['floor']->setData($viewData->floor());
            $forms['postal_code']->setData($viewData->postalCodes()->value());
            $forms['blocked']->getData();
            $forms['business_days']->getData($viewData->businessDays()->value());
        } catch (TypeError $e) {
            $data = null;
        }
    }

    public function mapFormsToData($forms, &$viewData)
    {
        $forms = iterator_to_array($forms);

        try {
            $data = new CreateCompanyRequest(
                $forms['vat']->getData(),
                $forms['name']->getData(),
                $forms['description']->getData(),
                $forms['web_site']->webSite(),
                $forms['phone_number1']->getData(),
                $forms['phone_number2']->getData(),
                $forms['email']->getData(),
                $forms['street_name']->getData(),
                $forms['number']->getData(),
                $forms['floor']->getData(),
                $forms['postal_code']->getData(),
                $forms['blocked']->getData(),
                $forms['business_days']->getData(),
            );
        } catch (TypeError $e) {
            $data = null;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'task_item',
        ]);
    }
}

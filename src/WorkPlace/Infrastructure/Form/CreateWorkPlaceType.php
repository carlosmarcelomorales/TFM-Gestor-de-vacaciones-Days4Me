<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Infrastructure\Form;


use League\Tactician\CommandBus;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Company\Application\Get\GetCompaniesRequest;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Application\Country\CountryResponse;
use TFM\HolidaysManagement\Country\Application\Country\GetCountriesRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

final class CreateWorkPlaceType extends AbstractType
{
    private CommandBus $commandBus;
    private Security  $security;
    private TranslatorInterface $translator;

    public function __construct(CommandBus $commandBus, Security $security, TranslatorInterface $translator)
    {
        $this->commandBus = $commandBus;
        $this->security = $security;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $user = $this->security->getUser();

        $companyUserFilter = ['id' => $user->departments()->workPlace()->companies()->id()->value()];
        $countryUser = $user->PostalCodes()->towns()[0]->Regions()->Countries()->id();
        $permitAccumulate = !$user->departments()->workPlace()->permitAccumulate();

        if (null !== $builder->getData()) {

            $companyUserFilter = ['id' => $builder->getData()->companies()->id()->value()];
            $countryUser = $builder->getData()->postalCodes()->towns()[0]->Regions()->Countries()->id();
            $permitAccumulate = !$builder->getData()->permitAccumulate();
        }


        $countries = $this->commandBus->handle(
            new GetCountriesRequest()
        );

        $companies = $this->commandBus->handle(new GetCompaniesRequest($companyUserFilter));

        $builder
            ->add(
                'id',
                HiddenType::class
            )
            ->add('companies', ChoiceType::class, [
                'choices' => $companies,
                'choice_value' => function ($company) {
                    if ($company instanceof Company) {
                        return $company->id();
                    }

                    return $company;
                },
                'choice_label' => function ($company, $key, $index) {
                    return $company->name();
                },
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'mapped' => true,
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'autocomplete' => 'off',
                ]
            ])
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 2,
                                'max' => 150,
                                'minMessage' => 'The name must have at least {{ limit }} characters',
                                'maxMessage' => 'The name must have at most {{ limit }} characters',
                            ]
                        ),
                        new NotBlank(
                            [
                                'message' => 'The field name cannot be empty',
                            ]
                        ),
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 1,
                                'max' => 255,
                                'maxMessage' => 'The description must have at most {{ limit }} characters',
                            ]
                        ),
                    ],
                    'attr' => [
                        'placeholder' => 'Enter the workplace description ',
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'The field email cannot be empty',
                            ]
                        ),
                        new Email(
                            [
                                'message' => 'Your email doesnt seems to be valid'
                            ]
                        ),
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'holidayStartYear',
                DateType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'input' => 'datetime_immutable',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'The field holidays start year cannot be empty!',
                            ]
                        ),
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'holidayEndYear',
                DateType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'input' => 'datetime_immutable',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'The field holidays end year cannot be empty!',
                            ]
                        ),
                        new Callback(function($object, ExecutionContextInterface $context) {
                            $start = $context->getRoot()->getData()['holidayStartYear'];
                            $end = $object;

                            if (is_a($start, DateTimeImmutable::class) && is_a($end, DateTimeImmutable::class)) {
                                if ($end->format('U') - $start->format('U') < 0) {
                                    $context
                                        ->buildViolation('Ending Date must be after start')
                                        ->addViolation();
                                }
                            }
                        }),
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add('permitAccumulate', CheckboxType::class,
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
            )
            ->add(
                'monthPermittedToAccumulate',
                ChoiceType::class,
                [
                    'choices'     => [
                          $this->translator->trans('workPlace.month.1')  => 1,
                          $this->translator->trans('workPlace.month.2')  => 2,
                          $this->translator->trans('workPlace.month.3')  => 3,
                          $this->translator->trans('workPlace.month.4')  => 4,
                          $this->translator->trans('workPlace.month.5')  => 5,
                          $this->translator->trans('workPlace.month.6')  => 6,
                          $this->translator->trans('workPlace.month.7')  => 7,
                          $this->translator->trans('workPlace.month.8')  => 8,
                          $this->translator->trans('workPlace.month.9')  => 9,
                          $this->translator->trans('workPlace.month.10') => 10,
                          $this->translator->trans('workPlace.month.11') => 11,
                          $this->translator->trans('workPlace.month.12') => 12,
                    ],
                    'required' => false,
                    'placeholder' => 'Select a month',
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off',
                        'disabled' =>$permitAccumulate,
                    ]
                ]
            )
            ->add(
                'streetName',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 1,
                                'max' => 100,
                                'minMessage' => 'The name must have at least {{ limit }} characters',
                                'maxMessage' => 'The name must have at most {{ limit }} characters',
                            ]
                        ),
                        new NotBlank(
                            [
                                'message' => 'The field street name cannot be empty',
                            ]
                        ),
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'number',
                NumberType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 1,
                                'max' => 20,
                                'minMessage' => 'The name must have at least {{ limit }} characters',
                                'maxMessage' => 'The name must have at most {{ limit }} characters',
                            ]
                        ),
                        new NotBlank(
                            [
                                'message' => 'The field number cannot be empty!',
                            ]
                        ),
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'floor',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 1,
                                'max' => 20,
                                'minMessage' => 'The name must have at least {{ limit }} characters',
                                'maxMessage' => 'The name must have at most {{ limit }} characters',
                            ]
                        ),
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'phoneNumber1',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 1,
                                'max' => 20,
                                'maxMessage' => 'The phone must have at most {{ limit }} characters',
                            ]
                        ),
                        new NotBlank(
                            [
                                'message' => 'The field phone workplace cannot be empty',
                            ]
                        ),
                        new Regex(
                            [
                                'pattern' => User::REGEX_PHONE,
                                'message' => 'The field phone is not valid',
                            ]
                        ),
                    ],
                    'attr' => [
                        'placeholder' => '+34655102030 ó 0034655102030',
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'phoneNumber2',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 1,
                                'max' => 20,
                                'maxMessage' => 'The phone must have at most {{ limit }} characters',
                            ]
                        ),
                        new Regex(
                            [
                                'pattern' => User::REGEX_PHONE,
                                'message' => 'The field phone is not valid',
                            ]
                        ),
                    ],
                    'attr' => [
                        'placeholder' => '+34655102030 ó 0034655102030',
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'blocked',
                CheckboxType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-check-input',
                        'autocomplete' => 'off'
                    ],
                    'label_attr' => [
                        'class' => 'form-check-label'
                    ]
                ]
            )
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
            ]);


        $builder->add('save', SubmitType::class);
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

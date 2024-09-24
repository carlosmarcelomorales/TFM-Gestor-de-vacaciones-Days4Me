<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Framework\Form;

use League\Tactician\CommandBus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use TFM\HolidaysManagement\Country\Application\Country\CountryResponse;
use TFM\HolidaysManagement\Country\Application\Country\GetCountriesRequest;
use TFM\HolidaysManagement\Role\Application\Search\GetRolesRequest;
use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\WorkPosition\Application\Get\GetWorkPositionsRequest;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;

final class CreateUserType extends AbstractType
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

        $rolesUser = [];

        $rolesFilter = in_array(Role::DEFAULT_SUPER_ADMIN_ROLE,
            $rolesUser) ? [''] : ['no_name' => Role::DEFAULT_SUPER_ADMIN_ROLE];

        $companyUserFilter = ['company' => $user->departments()->workPlace()->companies()->id()->value()];
        $workPositionUser = $user->workPositions()->id()->value();
        $countryUser = $user->PostalCodes()->towns()[0]->Regions()->Countries()->id();

        $permitAccumulate = !$user->departments()->workPlace()->permitAccumulate();

        if (null !== $builder->getData()) {

            $workPositionUser = $builder->getData()->workPositions()->id()->value();
            $companyUserFilter = ['company' => $builder->getData()->companies()->id()->value()];
            $countryUser = $builder->getData()->postalCodes()->towns()[0]->Regions()->Countries()->id();

            $rolesUser = $builder->getData()->getRoles();
            $rolesFilter = in_array(Role::DEFAULT_SUPER_ADMIN_ROLE,
                $rolesUser) ? [''] : ['no_name' => Role::DEFAULT_SUPER_ADMIN_ROLE];

            $permitAccumulate = !$builder->getData()->departments()->workPlace()->permitAccumulate();
        }

        $roles = $this->commandBus->handle(
            new GetRolesRequest($rolesFilter)
        );

        $workPositions = $this->commandBus->handle(
            new GetWorkPositionsRequest($companyUserFilter)
        );

        $countries = $this->commandBus->handle(
            new GetCountriesRequest()
        );

        $builder->add('id', HiddenType::class, [
            'required' => true,
            'mapped' => true
        ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'The name must have at least {{ limit }} characters',
                        'maxMessage' => 'The name must have at most {{ limit }} characters',
                    ]),
                    new NotBlank(
                        [
                            'message' => 'The field name cannot be empty!',
                        ]
                    ),
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'The lastname must have at least {{ limit }} characters',
                        'maxMessage' => 'The lastname must have at most {{ limit }} characters',
                    ]),
                    new NotBlank(
                        [
                            'message' => 'The field lastname cannot be empty!',
                        ]
                    ),
                ],
            ])
            ->add('emailAddress', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field emailAddress cannot be empty!',
                        ]
                    ),
                    new Email(
                        [
                            'message' => 'Your email doesnt seems to be valid'
                        ]
                    ),
                ],
            ])
            ->add('dni', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field dni cannot be empty!',
                        ]
                    ),
                ],
            ])
            ->add('availableDays', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field dni cannot be empty!',
                        ]
                    ),
                ],
                'attr' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
            ])
            ->add('accumulatedDays', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field accumulated days no be empty!',
                        ]
                    ),
                ],
                'attr' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                    'readonly' => $permitAccumulate,
                ],
            ])
            ->add('socialSecurityNumber', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field socialSecurityNumber cannot be empty!',
                        ]
                    ),
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field phone cannot be empty!',
                        ]
                    ),
                    new Regex(
                        [
                            'pattern' => User::REGEX_PHONE,
                            'message' => 'the field phone is not valid',
                        ]
                    ),
                ],
                'attr' => [
                    'placeholder' => '+34655102030 ó 0034655102030',
                    'class' => 'form-control form-control-sm',
                    'autocomplete' => 'off'
                ]
            ])
            ->add('workPositions', ChoiceType::class, [
                'choices' => $workPositions,
                'choice_value' => function ($workPosition) {
                    if ($workPosition instanceof WorkPosition) {
                        return $workPosition->id()->value();
                    }

                    return $workPosition;
                },
                'choice_label' => function ($workPosition, $key, $index) {
                    return $workPosition->name();
                },
                'data' => $workPositionUser,
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'autocomplete' => 'off',
                ]
            ]);


        $builder->add('roles', ChoiceType::class, [
            'choices' => $roles,
            'choice_value' => function ($role) {
                if ($role instanceof Role) {
                    return $role->name();
                }

                return $role;
            },
            'choice_label' => function ($role, $key, $index) {
                return $role->name();
            },
            'data' => [implode(',', $rolesUser)],
            'expanded' => false,
            'multiple' => true,
            'required' => true,
            'attr' => [
                'class' => 'form-control form-control-sm',
                'autocomplete' => 'on',
                'multiple' => 'multiple',
            ]
        ]);


        $builder->add('incorporationDate', DateType::class, [
            'widget' => 'single_text',
            // this is actually the default format for single_text
            'format' => 'yyyy-MM-dd',
            'input' => 'datetime_immutable'
        ])
            ->add('incorporationDate',
                DateType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'input' => 'datetime_immutable',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'The field incorporation Date cannot be empty!',
                            ]
                        ),
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ],
                    'label_attr' => [
                        'class' => 'form-check-label'
                    ]
                ])
            ->add('streetName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field street name cannot be empty!',
                        ]
                    ),
                ],
            ])
            ->add('number', IntegerType::class, [
                'required' => false,
            ])
            ->add('floor', TextType::class, [
                'required' => false,
            ]);
        if (!$this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('blocked', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                    'autocomplete' => 'off'
                ]
                ,
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ]);
        }
        $builder->add('country', ChoiceType::class, [
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

        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'task_item',
            //'data_class' => User::class
        ]);
    }
}
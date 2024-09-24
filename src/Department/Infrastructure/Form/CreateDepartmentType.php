<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Infrastructure\Form;

use League\Tactician\CommandBus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\WorkPlace\Application\Get\GetWorkPlacesRequest;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;


final class CreateDepartmentType extends AbstractType
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
        $companyUserFilter = ['company' => $user->departments()->workPlace()->companies()->id()->value()];
        $workPlaceUser = $user->workPositions()->id()->value();

        if (null !== $builder->getData()) {

            $workPlaceUser = $builder->getData()->workPlace()->id()->value();
            $companyUserFilter = ['company' => $builder->getData()->workPlace()->companies()->id()->value()];

        }

        $workPlaces = $this->commandBus->handle(
            new GetWorkPlacesRequest($companyUserFilter)
        );


        $builder
            ->add(
                'id',
                HiddenType::class
            )
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 1,
                                'max' => 150,
                                'maxMessage' => 'The department name must have at most {{ limit }} characters',
                            ]
                        ),
                        new NotBlank(
                            [
                                'message' => 'The field name cannot be empty',
                            ]
                        )
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
                        'placeholder' => 'Enter the department description ',
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'phoneNumber',
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
                                'message' => 'The field phone department cannot be empty',
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
                ]
            )
            ->add(
                'phoneExtension',
                NumberType::class,
                [
                    'required' => false,
                    'attr' => [
                        'placeholder' => '123',
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add('workplace', ChoiceType::class, [
                'choices' => $workPlaces,
                'choice_value' => function ($workPlace) {
                    if ($workPlace instanceof WorkPlace) {
                        return $workPlace->id()->value();
                    }

                    return $workPlace;
                },
                'choice_label' => function ($workPlace, $key, $index) {
                    return $workPlace->name();
                },
                'data' => $workPlaceUser,
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'autocomplete' => 'off',
                ]
            ])
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
            );

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

<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Infrastructure\Form;


use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate\StatusRequest;
use TFM\HolidaysManagement\TypeRequest\Domain\Model\Aggregate\TypeRequest;


final class CreateRequestType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // @TODO: Add Calendar, Documents, Request hystory and others TODO's

        $builder
            ->add(
                'id',
                HiddenType::class
            );

        $builder->add(
            'statusRequest',
            EntityType::class,
            [
                'class' => StatusRequest::class,
                'mapped' => true,
                'choice_label' => function (StatusRequest $statusRequest) {
                    return $statusRequest->__toString();
                },
                'choice_value' => function (StatusRequest $statusRequest = null) {
                    if ($statusRequest) {
                        return $statusRequest->id()->value();
                    }
                },
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-sm select2',
                    'autocomplete' => 'off'
                ],
            ]
        );

        $builder
            ->add(
                'typesRequest',
                EntityType::class,
                [
                    'class' => TypeRequest::class,
                    'mapped' => true,
                    'choice_label' => function (TypeRequest $typeRequest) {
                        return $typeRequest->__toString();
                    },
                    'choice_value' => function (TypeRequest $typeRequest = null) {
                        if ($typeRequest) {
                            return $typeRequest->id()->value();
                        }
                    },
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true,
                    'placeholder' => 'Select options',
                    'attr' => [
                        'class' => 'form-control form-control-sm select2',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'requestPeriodStart',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'input' => 'datetime_immutable',
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ],
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' =>  $this->translator->trans('message.constraint.empty'),
                            ]
                        )

                    ]
                ]
            )
            ->add(
                'requestPeriodEnd',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'input' => 'datetime_immutable',
                    'attr' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ],
                    'required' => true,
                    'constraints' => [
                        new Callback(function ($object, ExecutionContextInterface $context) {
                            $start = $context->getRoot()->getData()['requestPeriodStart'];
                            $end = $object;

                            if (is_a($start, DateTimeImmutable::class) && is_a($end, DateTimeImmutable::class)) {
                                if ($end->format('U') - $start->format('U') < 0) {
                                    $context
                                        ->buildViolation('EndingDate must be after start')
                                        ->addViolation();
                                }
                            }
                        }),
                        new NotBlank(
                            [
                                'message' =>  $this->translator->trans('message.constraint.empty'),
                            ]
                        )
                    ]
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => true,
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
                        'placeholder' => 'Enter the request description ',
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            );

        $builder->add('save', SubmitType::class);
        $builder->add('annulled', SubmitType::class);
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

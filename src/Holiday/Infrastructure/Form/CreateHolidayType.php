<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Infrastructure\Form;


use DateTimeImmutable;
use League\Tactician\CommandBus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use TFM\HolidaysManagement\WorkPlace\Application\Get\GetWorkPlacesRequest;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;

final class CreateHolidayType extends AbstractType
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

            $workPlaceUser = $builder->getData()->workPlaces()->id()->value();
            $companyUserFilter = ['company' => $builder->getData()->workPlaces()->companies()->id()->value()];

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
                'holidayName',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 2,
                                'max' => 100,
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
                        'placeholder' => 'Enter the holiday days like St. Patrick\'s Day, Easter Day, Christmas...',
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'startDay',
                DateType::class,
                [
                    'required' => true,
                    'widget'   => 'single_text',
                    'format'   => 'yyyy-MM-dd',
                    'input'    => 'datetime_immutable',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'The field start day cannot be empty!',
                            ]
                        ),
                    ],
                    'attr'     => [
                        'class'        => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'endDay',
                DateType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'input' => 'datetime_immutable',
                    'constraints' => [
                        new Callback(function($object, ExecutionContextInterface $context) {
                            $start = $context->getRoot()->getData()['startDay'];
                            $end = $object;

                            if (is_a($start, DateTimeImmutable::class) && is_a($end, DateTimeImmutable::class)) {
                                if ($end->format('U') - $start->format('U') < 0) {
                                    $context
                                        ->buildViolation('Ending Date must be same start')
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
            ->add('workplaces', ChoiceType::class, [
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

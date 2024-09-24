<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Infrastructure\Form;


use League\Tactician\CommandBus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use TFM\HolidaysManagement\Department\Application\Get\GetDepartmentsRequest;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;

final class CreateWorkPositionType extends AbstractType
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
        $companyUserFilter = ['company' => $user->companies()->id()->value()];
        $departmentUser = null;

        if (null !== $builder->getData()) {

            $departmentUser = $builder->getData()->departments()->id()->value();
        }

        $departments = $this->commandBus->handle(
            new GetDepartmentsRequest($companyUserFilter)
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
                        'placeholder' => 'Enter the types of work position: director, secretay, systems techinician...',
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off'
                    ]
                ]
            )
            ->add(
                'headDepartment',
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
            ->add('departments', ChoiceType::class, [
                'choices' => $departments,
                'choice_value' => function ($department) {
                    if ($department instanceof Department) {
                        return $department->id()->value();
                    }

                    return $department;
                },
                'choice_label' => function ($department, $key, $index) {
                    return $department->name();
                },
                'data' => $departmentUser,
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

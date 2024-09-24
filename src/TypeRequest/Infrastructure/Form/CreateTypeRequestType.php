<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Infrastructure\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CreateTypeRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
            'name',
            TextType::class,
            [
                'required'    => true,
                'constraints' => [
                    new Length(
                        [
                            'min'        => 2,
                            'max'        => 100,
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
                'attr'        => [
                    'placeholder'  => 'Enter the types of request: vacations, sick leave, personal negotiations',
                    'class'        => 'form-control form-control-sm',
                    'autocomplete' => 'off'
                ]
            ]
        );

        $builder->add('save', SubmitType::class);
        $builder->add('cancel', ResetType::class);
    }
}
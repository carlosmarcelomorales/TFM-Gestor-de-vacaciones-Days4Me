<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Framework\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

final class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('company_vat', TextType::class, [
            'required' => true,
            //'mapped' => false,
            'constraints' => [
                new Regex(
                    [
                        'pattern' => Company::REGEX_VAT,
                        'message' => 'The vat is incorrectly',
                    ]
                ),
            ],
            'attr' => ['class' => 'form-control py-4']
        ]);

        $builder->add('company_name', TextType::class, [
            'required' => true,
            //'mapped' => false,
            'constraints' => [
                new Length([
                    'min' => 1,
                    'max' => 100,
                    'minMessage' => 'The name must have at least {{ limit }} characters',
                    'maxMessage' => 'The name must have at most {{ limit }} characters',
                ]),
            ],
            'attr' => ['class' => 'form-control py-4']
        ])
            ->add('phone_number', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => Company::MAX_LENGTH_PHONE,
                        'minMessage' => 'The phone number must have at least {{ limit }} characters',
                        'maxMessage' => 'The phone number must have at most {{ limit }} characters',
                    ]),
                ],
                'attr' => ['class' => 'form-control py-4']
            ])
            ->add('postal_code', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 5,
                        'minMessage' => 'The postal code must have at least {{ limit }} characters',
                        'maxMessage' => 'The postal code must have at most {{ limit }} characters',
                    ]),
                ],
                'attr' => ['class' => 'form-control py-4']
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 100,
                        'minMessage' => 'The name must have at least {{ limit }} characters',
                        'maxMessage' => 'The name must have at most {{ limit }} characters',
                    ]),
                ],
                'attr' => ['class' => 'form-control py-4']
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field email cannot be empty',
                        ]
                    ),

                ],
                'attr' => ['class' => 'form-control py-4']
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The passwords must match',
                'required' => true,
                'constraints' => [
                    new Regex(
                        [
                            'pattern' => User::REGEX_PASSWORD,
                            'message' => 'The password must be at least 8 characters long, one uppercase letter, one lowercase letter, and one number. The letter Ñ and other symbols are not allowed',
                        ]
                    ),
                ],
            ])
            ->add('submit', SubmitType::class);
    }

}
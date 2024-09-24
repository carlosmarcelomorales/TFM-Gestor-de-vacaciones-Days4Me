<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Framework\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use TFM\HolidaysManagement\User\Application\Security\UpdatePasswordRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

class RestoreEmailAndPasswordType extends AbstractType implements DataMapperInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper($this);

        $builder
            ->add('plain_password', RepeatedType::class, [
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
            ->add('emailAddress', TextType::class, [
                'required' => true,
                'mapped' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field emailAddress cannot be empty!',
                        ]
                    ),
                ],
            ])
            ->add('submit', SubmitType::class);
    }


    public function getBlockPrefix()
    {
        return 'ui_restore_password_type';
    }

    public function mapDataToForms($data, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $data) {
            return;
        }

        // invalid data type
        if (!$data instanceof User) {
            throw new UnexpectedTypeException($data, User::class);
        }

        $forms = iterator_to_array($forms);
        $forms['emailAddress']->setData($data->emailAddress());
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        if (is_null($forms['plain_password']->getData())) {
            $failure = new TransformationFailedException('The passwords must match');
            $failure->setInvalidMessage('The passwords  dont  match');
            throw $failure;
        }

        $data = new UpdatePasswordRequest(
            $data->id()->value(),
            $forms['plain_password']->getData(),
            $forms['emailAddress']->getData()
        );
    }
}

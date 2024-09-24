<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Framework\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use TFM\HolidaysManagement\User\Application\Security\RequestTokenRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

class RequestTokenType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper($this);

        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'The field email cannot be empty',
                        ]
                    ),
                    new Length([
                        'min' => 1,
                        'max' => User::MAX_LENGTH_EMAIL,
                        'minMessage' => 'The email must have at least {{ limit }} characters',
                        'maxMessage' => 'The email must have at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'ui_request_token_type';
    }

    public function mapDataToForms($data, $forms)
    {
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = new RequestTokenRequest($forms['email']->getData(), null, null);
    }
}

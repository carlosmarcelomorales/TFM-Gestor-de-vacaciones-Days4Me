<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Mail\Infrastructure\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Mail\Domain\Model\Service\SendEmailService;
use TFM\HolidaysManagement\Mail\Domain\Model\Service\SendWelcomeEmailService;

final class SendWelcomeCompanyEmail implements SendEmailService
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;

    public function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function __invoke(string $email)
    {
        $message = (new TemplatedEmail())
            ->subject($this->translator->trans('email.company.welcome.subject'))
            ->from(new Address('damepokalor@gmail.com', $this->translator->trans('email.brand')))
            ->to($email)
            ->htmlTemplate('mail/email/welcome.html.twig')
            ->context([
                'emailUser' => $email
                      ]);

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
        }
    }
}

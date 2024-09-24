<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Mail\Application;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SendRecoveryPasswordEmail
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

    public function __invoke(string $email, string $token, ?string $route)
    {
        if ('Created' === $route) {
            $message = (new TemplatedEmail())
                ->subject($this->translator->trans('email.user.welcome.subject'))
                ->from(new Address('damepokalor@gmail.com', $this->translator->trans('email.user')))
                ->to($email)
                ->htmlTemplate('mail/email/recovery_password.html.twig')
                ->context(
                    [
                        'token'     => $token,
                        'emailUser' => $email,
                        'subject'   => $this->translator->trans('email.user.welcome.subject'),
                        'texto'     => $this->translator->trans('email.user.content')
                    ]
                );

        } else {
            $message = (new TemplatedEmail())
                ->subject($this->translator->trans('email.recovery.subject'))
                ->from(new Address('damepokalor@gmail.com',$this->translator->trans('email.recovery')))
                ->to($email)
                ->htmlTemplate('mail/email/recovery_password.html.twig')
                ->context(
                    [
                        'token'     => $token,
                        'emailUser' => $email,
                        'subject'   => $this->translator->trans('email.recovery.subject'),
                        'texto'     => $this->translator->trans('email.recover.content')
                    ]
                );
        }

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
        }
    }
}

<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Mail\Application;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SendNewRequestEmail
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

    public function __invoke(array $emails, string $emailUser, int $availableDays, int $accumulateDays, string $status)
    {
        $toAddresses = $emails;

        $message = (new TemplatedEmail())
            ->subject(
                $this->translator->trans('email.request.subject') . ' - ' .
                        $this->translator->trans('request.statusRequest') . ' ' .
                        $status
            )
            ->from(new Address('damepokalor@gmail.com', $this->translator->trans('email.request')))
            ->to(...$toAddresses)
            ->htmlTemplate('mail/email/new_request.html.twig')
            ->context([
                          'emailUser'       => $emailUser,
                          'availableDays'   => $availableDays,
                          'accumulateDays'  => $accumulateDays,
                          'statusRequest'   => $status
                      ]);

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
        }
    }
}

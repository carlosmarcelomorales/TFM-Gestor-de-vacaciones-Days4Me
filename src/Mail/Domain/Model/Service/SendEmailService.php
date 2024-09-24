<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Mail\Domain\Model\Service;

interface SendEmailService
{
    public function __invoke(string $email);
}

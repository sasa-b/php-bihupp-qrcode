<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Exception;

final class InvalidLengthException extends \DomainException
{
    public function __construct(string $line, int $maxLength, int $actualLength)
    {
        parent::__construct("$line exceeds maximum length of $maxLength characters, got: $actualLength.");
    }
}

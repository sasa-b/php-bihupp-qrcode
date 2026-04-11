<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Exception;

use Sco\BihuppQRCode\BihuppQRCodeException;

final class InvalidLengthException extends BihuppQRCodeException
{
    public function __construct(string $line, int $maxLength, int $actualLength)
    {
        parent::__construct("$line exceeds maximum length of $maxLength characters, got: $actualLength.");
    }
}

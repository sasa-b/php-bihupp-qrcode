<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Exception;

use Sco\BihuppQRCode\BihuppQRCodeException;

final class InvalidLengthException extends BihuppQRCodeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function max(string $line, int $maxLength, int $actualLength): self
    {
        return new self("$line exceeds maximum length of $maxLength characters, got: $actualLength.");
    }

    public static function min(string $line, int $minLength, int $actualLength): self
    {
        return new self("$line must be at least $minLength characters long, got $actualLength.");
    }
}

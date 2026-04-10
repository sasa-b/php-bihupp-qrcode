<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Poziv na broj.
 */
final readonly class PaymentReference extends Line
{
    public const int MAX_LENGTH = 10;

    public function __construct(public string $value)
    {
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            throw new InvalidValueException('Payment reference must be a 10 digit integer.');
        }

        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

final readonly class RevenueType extends Line
{
    public const int MAX_LENGTH = 6;

    public function __construct(public string $value)
    {
        if (preg_match('/^[0-9]{6}$/D', $value) !== 1) {
            throw new InvalidValueException("Invalid revenue type, must be 6 digits, got: $value.");
        }

        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }
}

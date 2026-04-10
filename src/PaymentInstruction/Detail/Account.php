<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Račun pošiljaoca.
 */
final readonly class Account extends Line
{
    public const int MAX_LENGTH = 16;

    public function __construct(public string $value)
    {
        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Sender;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Račun pošiljaoca.
 */
final readonly class SenderAccount extends Line
{
    public const int MAX_LENGTH = 16;

    public function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

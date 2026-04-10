<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * JMBG.
 */
final readonly class SenderTaxId extends Line
{
    public const int MAX_LENGTH = 13;

    private function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

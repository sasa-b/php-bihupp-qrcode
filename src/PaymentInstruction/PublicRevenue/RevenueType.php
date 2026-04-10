<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Line;

final readonly class RevenueType extends Line
{
    public const int MAX_LENGTH = 6;

    private function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

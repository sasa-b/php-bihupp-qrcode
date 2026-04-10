<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Poziv na broj.
 */
final readonly class PaymentReference extends Line
{
    public const int MAX_LENGTH = 10;

    public function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

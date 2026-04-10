<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Budžetska organizacija.
 */
final readonly class BudgetOrgCode extends Line
{
    public const int MAX_LENGTH = 7;

    public function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Opština.
 */
final readonly class MunicipalCode extends Line
{
    public const int MAX_LENGTH = 3;

    public function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Line;

final readonly class TaxPeriodDate extends Line
{
    public const int MAX_LENGTH = 8;

    private function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function fromDate(\DateTimeInterface $date): self
    {
        return new self($date->format('d/m/y'));
    }
}

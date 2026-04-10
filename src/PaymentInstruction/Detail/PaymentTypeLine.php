<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Hitno.
 */
final readonly class PaymentTypeLine extends Line
{
    public const int MAX_LENGTH = 1;

    private function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function from(PaymentType $type): self
    {
        return new self($type->value);
    }
}

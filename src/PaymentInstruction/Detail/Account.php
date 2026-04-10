<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Line;

abstract readonly class Account extends Line
{
    public static function fromInt(int $value): static
    {
        // TODO: pad numeric value with zeros to 16 digits
        return new static((string) $value);
    }

    public static function fromFloat(float $value): static
    {
        return new static(str_pad((string) (int) $value, 16, '0', STR_PAD_LEFT));
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Opština.
 */
final readonly class MunicipalCode extends Line
{
    public const int MAX_LENGTH = 3;

    /**
     * @throws InvalidValueException
     */
    public function __construct(public string $value)
    {
        if (preg_match('/^[0-9]{3}$/', $value) !== 1) {
            throw new InvalidValueException("Invalid municipal code must be a 3 digit integer, got: $value.");
        }
    }
}

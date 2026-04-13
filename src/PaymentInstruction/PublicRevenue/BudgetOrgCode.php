<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Budžetska organizacija.
 */
final readonly class BudgetOrgCode extends Line
{
    public const int MAX_LENGTH = 7;

    /**
     * @throws InvalidValueException
     */
    public function __construct(public string $value)
    {
        if (!preg_match('/^[0-9]{7}$/', $value)) {
            throw new InvalidValueException("Budget organization code must be a 7 digits integer, got: $value.");
        }
    }

    public static function empty(): self
    {
        return new self(str_pad('0', self::MAX_LENGTH, '0'));
    }
}

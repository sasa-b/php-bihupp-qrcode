<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Hitno.
 */
final readonly class PaymentPriority extends Line
{
    public const int MAX_LENGTH = 1;

    public function __construct(public string $value = Priority::Regular->value)
    {
        if (!in_array($value, array_column(Priority::cases(), 'value'), true)) {
            throw new InvalidValueException("Invalid payment priority format, must be N or D, got: $value.");
        }

        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function from(Priority $type): self
    {
        return new self($type->value);
    }

    public static function regular(): self
    {
        return self::from(Priority::Regular);
    }

    public static function urgent(): self
    {
        return self::from(Priority::Urgent);
    }
}

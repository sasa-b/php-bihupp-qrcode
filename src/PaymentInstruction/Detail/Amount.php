<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Iznos.
 */
final readonly class Amount extends Line
{
    public const int MAX_LENGTH = 15;

    public string $value;

    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     * @throws InvalidValueException
     */
    public function __construct(
        string $value,
    ) {
        $value = str_replace('.', '', $value);

        if (preg_match('/\D/', $value) === 1) {
            throw new InvalidValueException('Amount must be a zero padded integer number (value in pennies).');
        }

        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);

        $this->value = str_pad($value, self::MAX_LENGTH, '0', STR_PAD_LEFT);
    }

    public static function fromInt(int $value): self
    {
        return new self((string) $value);
    }

    public static function fromFloat(float $value): self
    {
        return new self((string) $value);
    }

    public function toInteger(): int
    {
        return (int) $this->value;
    }

    public function toFloat(): float
    {
        return round($this->toInteger() / 100, 2);
    }
}

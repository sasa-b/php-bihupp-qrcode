<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidFormatException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
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
     */
    public function __construct(
        string $value,
    ) {
        $value = str_replace('.', '', $value);

        if (preg_match('/\D/', $value) === 1) {
            throw new InvalidFormatException('Amount must be a zero padded integer number (value in pennies).');
        }

        self::validate(__CLASS__, $value, self::MAX_LENGTH);

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
}

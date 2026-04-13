<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Poziv na broj.
 */
final readonly class PaymentReference extends Line
{
    public const int MAX_LENGTH = 10;

    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     * @throws InvalidValueException
     */
    public function __construct(public string $value)
    {
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            throw new InvalidValueException('Payment reference must be a 10 digit integer.');
        }

        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function empty(): self
    {
        return new self(str_pad('0', self::MAX_LENGTH, '0'));
    }
}

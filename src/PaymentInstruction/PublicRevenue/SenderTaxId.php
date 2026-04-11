<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * JMBG.
 */
final readonly class SenderTaxId extends Line
{
    public const int MAX_LENGTH = 13;

    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     * @throws InvalidValueException
     */
    public function __construct(public string $value)
    {
        if (!preg_match('/^\d{13}$/', $value)) {
            throw new InvalidValueException('Invalid tax ID, must be 13 digits, got: '.$value);
        }

        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }
}

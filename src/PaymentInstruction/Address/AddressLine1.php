<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Address;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Adresa uplatioca/primaoca (ulica i broj).
 */
final readonly class AddressLine1 extends Line
{
    public const int MAX_LENGTH = 50;

    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     */
    public function __construct(public string $value)
    {
        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function from(string $street, string $number): self
    {
        return new self(trim("$street $number"));
    }
}

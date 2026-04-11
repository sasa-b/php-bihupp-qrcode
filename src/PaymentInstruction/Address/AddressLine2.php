<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Address;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Adresa uplatioca/primaoca (poštanski broj i mjesto).
 */
final readonly class AddressLine2 extends Line
{
    public const int MAX_LENGTH = 25;

    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     */
    private function __construct(public string $value)
    {
        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function from(string $postcode, string $town): self
    {
        return new self(trim("$postcode $town"));
    }
}

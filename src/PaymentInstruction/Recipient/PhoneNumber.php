<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Recipient;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidFormatException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Broj telefona uplatioca/primaoca.
 *
 * We are using the E.164 format.
 *
 * An E.164 number has three components:
 *    - The prefix ”+”.
 *    - A 1-3 digit country code.
 *    - A subscriber number.
 */
final readonly class PhoneNumber extends Line
{
    public const int MAX_LENGTH = 15;

    /**
     * @throws InvalidFormatException if the phone number does not start with +
     * @throws InvalidLengthException
     */
    public function __construct(public string $value)
    {
        if (!str_starts_with($this->value, '+')) {
            throw new InvalidFormatException('Phone number must be in E.164 format starting with +.');
        }

        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

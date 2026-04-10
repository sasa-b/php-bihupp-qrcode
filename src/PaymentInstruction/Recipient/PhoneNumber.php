<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Recipient;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
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

    public string $value;

    /**
     * @throws InvalidValueException  if the phone number does not start with +
     * @throws InvalidLengthException
     */
    public function __construct(string $value)
    {
        $value = str_replace(' ', '', $value);

        if (!str_starts_with($value, '+')) {
            throw new InvalidValueException('Phone number must be in E.164 format starting with +.');
        }

        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);

        $this->value = $value;
    }
}

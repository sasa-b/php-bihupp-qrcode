<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Recipient;

use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Račun primaoca.
 */
final readonly class RecipientAccount extends Line
{
    public const int MAX_LENGTH = 339;

    /**
     * @throws InvalidLengthException
     */
    private function __construct(public string $value)
    {
        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function from(Account ...$account): self
    {
        if (count($account) === 0) {
            throw new InvalidValueException('At least one account is required.');
        }

        if (count($account) > 20) {
            throw new InvalidValueException('Maximum of 20 accounts is allowed.');
        }

        return new self(implode(',', array_map(static fn (Account $a) => $a->value, $account)));
    }
}

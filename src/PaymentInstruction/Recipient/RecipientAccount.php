<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Recipient;

use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Račun primaoca.
 */
final readonly class RecipientAccount extends Line
{
    public const int MAX_LENGTH = 339;

    public string $value;

    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     * @throws InvalidValueException
     */
    public function __construct(Account ...$account)
    {
        if (count($account) === 0) {
            throw new InvalidValueException('At least one account is required.');
        }

        if (count($account) > 20) {
            throw new InvalidValueException('Maximum of 20 accounts is allowed.');
        }

        $value = implode(',', array_map(static fn (Account $a) => $a->value, $account));

        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);

        $this->value = $value;
    }
}

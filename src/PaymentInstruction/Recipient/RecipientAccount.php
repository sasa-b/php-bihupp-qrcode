<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Recipient;

use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

/**
 * Račun primaoca.
 */
final readonly class RecipientAccount extends Account
{
    public const int MAX_LENGTH = 339;

    /**
     * @throws InvalidLengthException
     */
    public function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

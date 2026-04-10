<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Sender;

use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;

/**
 * Račun pošiljaoca.
 */
final readonly class SenderAccount extends Account
{
    public const int MAX_LENGTH = 16;

    public function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

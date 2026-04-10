<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Recipient;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
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
    public function __construct(public string $value)
    {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

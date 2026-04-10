<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Svrha doznake.
 */
final readonly class PaymentPurpose extends Line
{
    public const int MAX_LENGTH = 110;

    public string $value;

    /**
     * @throws InvalidLengthException
     */
    public function __construct(
        string $value,
    ) {
        $this->value = str_replace(" ", "\n", $value);

        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

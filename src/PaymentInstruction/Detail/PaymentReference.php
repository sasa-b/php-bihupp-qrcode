<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Poziv na broj/referenca.
 */
final readonly class PaymentReference extends Line
{
    public const int MAX_LENGTH = 30;

    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     */
    public function __construct(
        public string $value,
    ) {
        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }
}

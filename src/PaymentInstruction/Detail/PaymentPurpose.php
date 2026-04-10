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

    /**
     * @throws InvalidLengthException
     */
    public function __construct(
        public string $value,
    ) {
        $length = strlen($this->value);
        if ($length > self::MAX_LENGTH) {
            throw new InvalidLengthException('Payment purpose', self::MAX_LENGTH, $length);
        }
    }
}

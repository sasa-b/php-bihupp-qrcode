<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Iznos.
 */
final readonly class Amount extends Line
{
    public const int MAX_LENGTH = 15;

    /**
     * @throws InvalidLengthException
     */
    public function __construct(
        public string $value,
    ) {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }
}

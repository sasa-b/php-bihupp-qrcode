<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Valuta.
 *
 * ISO 4217 currency code.
 */
final readonly class Currency extends Line
{
    public const int MAX_LENGTH = 3;

    private const string BAM = 'BAM';

    public function __construct() {}

    #[\Override]
    public function __toString(): string
    {
        return self::BAM.Line::END;
    }
}

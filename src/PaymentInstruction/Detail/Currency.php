<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Line;

/**
 * Valuta.
 */
final readonly class Currency extends Line
{
    public const int MAX_LENGTH = 3;

    private const string BAM = 'BAM';

    public function __construct(
        public string $value = self::BAM,
    ) {}
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

/**
 * Verzija BIH UPP QRCode standarda.
 */
final readonly class Version extends Line
{
    private const string LATEST = 'BIHUPP10';

    public function __construct(
        public string $value = self::LATEST,
    ) {}
}

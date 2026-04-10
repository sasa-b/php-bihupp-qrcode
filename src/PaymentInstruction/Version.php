<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

/**
 * Verzija BIH UPP QRCode standarda.
 */
final readonly class Version extends Line
{
    public const int MAX_LENGTH = 8;

    private const string LATEST = 'BIHUPP10';

    public function __construct() {}

    #[\Override]
    public function __toString(): string
    {
        return self::LATEST.Line::END;
    }
}

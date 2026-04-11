<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QROptions;

final readonly class QRCode
{
    public function __construct(
        private PaymentInstruction $data,
    ) {}

    public function toSvg(): string
    {
        $options = new QROptions();
        $options->eccLevel = EccLevel::L; // ~7% error correction (Level L)
        $options->outputBase64 = false;

        return (new \chillerlan\QRCode\QRCode($options))->render((string) $this->data);
    }
}

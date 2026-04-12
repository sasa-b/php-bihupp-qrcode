<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;

final readonly class ChillerlanQRCodeRenderer implements Renderer
{
    private QRCode $qrCode;

    public function __construct()
    {
        $options = new QROptions();
        $options->eccLevel = EccLevel::L; // ~7% error correction (Level L)

        $this->qrCode = new QRCode($options);
    }

    public function render(PaymentInstruction $data, RenderStrategy $strategy): string
    {
        $options = new QROptions();

        $strategy->apply($options);

        return $this->qrCode->setOptions($options)->render((string) $data);
    }
}

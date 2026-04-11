<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\RenderStrategy;

use chillerlan\QRCode\QRCode;
use Sco\BihuppQRCode\QRCode\ImageRenderStrategy;

final readonly class Svg implements ImageRenderStrategy
{
    public function apply(QRCode $qrcode): void
    {
        $qrcode->setOptions([
            'outputBase64' => false,
        ]);
    }
}

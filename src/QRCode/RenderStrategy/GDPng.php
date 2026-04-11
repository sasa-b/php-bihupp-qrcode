<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\RenderStrategy;

use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use Sco\BihuppQRCode\QRCode\Exception\MissingImageExtension;
use Sco\BihuppQRCode\QRCode\ImageRenderStrategy;

final readonly class GDPng implements ImageRenderStrategy
{
    public function apply(QRCode $qrcode): void
    {
        if (!extension_loaded('gd')) {
            throw new MissingImageExtension('GD');
        }

        $qrcode->setOptions([
            'outputInterface' => QRGdImagePNG::class,
            'quality' => 90,
            'outputBase64' => false,
        ]);
    }
}

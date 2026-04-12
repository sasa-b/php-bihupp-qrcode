<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\RenderStrategy;

use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QROptions;
use Sco\BihuppQRCode\QRCode\Exception\MissingImageExtension;
use Sco\BihuppQRCode\QRCode\ImageRenderStrategy;

final readonly class GDPng implements ImageRenderStrategy
{
    public function apply(QROptions $options): void
    {
        if (!extension_loaded('gd')) {
            throw new MissingImageExtension('GD');
        }
        $options->outputInterface = QRGdImagePNG::class;
        $options->quality = 90;
        $options->outputBase64 = false;
    }
}

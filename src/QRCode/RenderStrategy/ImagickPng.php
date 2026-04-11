<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\RenderStrategy;

use chillerlan\QRCode\Output\QRImagick;
use chillerlan\QRCode\QRCode;
use Sco\BihuppQRCode\QRCode\Exception\MissingImageExtension;
use Sco\BihuppQRCode\QRCode\ImageRenderStrategy;

final readonly class ImagickPng implements ImageRenderStrategy
{
    public function apply(QRCode $qrcode): void
    {
        if (!extension_loaded('imagick')) {
            throw new MissingImageExtension('Imagick');
        }

        $qrcode->setOptions([
            'outputInterface' => QRImagick::class,
            'imagickFormat' => 'png32',
            'quality' => 90,
            'outputBase64' => false,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\RenderStrategy;

use chillerlan\QRCode\Output\QRImagick;
use chillerlan\QRCode\QROptions;
use Sco\BihuppQRCode\QRCode\Exception\MissingImageExtension;
use Sco\BihuppQRCode\QRCode\ImageRenderStrategy;

/**
 * @Deprecated use ImagickPng instead
 */
final readonly class ImagickJpeg implements ImageRenderStrategy
{
    public function apply(QROptions $options): void
    {
        if (!extension_loaded('imagick')) {
            throw new MissingImageExtension('Imagick');
        }
        $options->outputInterface = QRImagick::class;
        $options->imagickFormat = 'jpeg';
        $options->quality = 90;
        $options->outputBase64 = false;
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\RenderStrategy;

use chillerlan\QRCode\QROptions;
use Sco\BihuppQRCode\QRCode\ImageRenderStrategy;

final readonly class Svg implements ImageRenderStrategy
{
    public function apply(QROptions $options): void
    {
        $options->outputBase64 = false;
    }
}

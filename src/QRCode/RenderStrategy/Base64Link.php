<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\RenderStrategy;

use chillerlan\QRCode\QROptions;
use Sco\BihuppQRCode\QRCode\ImageRenderStrategy;
use Sco\BihuppQRCode\QRCode\RenderStrategy;

final readonly class Base64Link implements RenderStrategy
{
    public function __construct(public ImageRenderStrategy $imageRenderStrategy = new Svg()) {}

    public function apply(QROptions $options): void
    {
        $this->imageRenderStrategy->apply($options);
        // Override
        $options->outputBase64 = true;
    }
}

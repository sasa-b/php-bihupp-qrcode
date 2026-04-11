<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\RenderStrategy;

use chillerlan\QRCode\QRCode;
use Sco\BihuppQRCode\QRCode\ImageRenderStrategy;
use Sco\BihuppQRCode\QRCode\RenderStrategy;

final readonly class Base64Link implements RenderStrategy
{
    public function __construct(public ImageRenderStrategy $imageRenderStrategy = new Svg()) {}

    public function apply(QRCode $qrcode): void
    {
        $this->imageRenderStrategy->apply($qrcode);

        $qrcode->setOptions([
            'outputBase64' => true,
        ]);
    }
}

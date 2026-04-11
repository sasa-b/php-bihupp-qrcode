<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode;

use chillerlan\QRCode\QRCode;

interface ImageRenderStrategy extends RenderStrategy
{
    public function apply(QRCode $qrcode): void;
}

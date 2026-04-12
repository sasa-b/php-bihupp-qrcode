<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode;

use chillerlan\QRCode\QROptions;

interface RenderStrategy
{
    public function apply(QROptions $options): void;
}

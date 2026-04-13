<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode;

abstract readonly class ReadSource
{
    public function __construct(
        public string $value,
        public ImageExtension $extension = ImageExtension::GD,
    ) {}
}

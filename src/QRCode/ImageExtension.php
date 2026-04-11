<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode;

enum ImageExtension
{
    case Imagick;
    case GD;
}

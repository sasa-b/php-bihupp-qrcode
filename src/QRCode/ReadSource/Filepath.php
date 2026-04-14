<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\ReadSource;

use Sco\BihuppQRCode\QRCode\ImageExtension;
use Sco\BihuppQRCode\QRCode\ReadSource;

final readonly class Filepath extends ReadSource
{
    /**
     * @param string         $value     Absolute or relative path to the QR code image file
     * @param ImageExtension $extension Image processing extension to use
     */
    public function __construct(string $value, ImageExtension $extension = ImageExtension::GD)
    {
        parent::__construct($value, $extension);
    }
}

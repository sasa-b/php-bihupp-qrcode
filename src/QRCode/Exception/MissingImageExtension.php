<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\Exception;

use Sco\BihuppQRCode\BihuppQRCodeException;

final class MissingImageExtension extends BihuppQRCodeException
{
    public function __construct(string $extension = 'GD/Imagick')
    {
        parent::__construct("$extension extension is required for PNG/JPG QRCode render strategies.");
    }
}

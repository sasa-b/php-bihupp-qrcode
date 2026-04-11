<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode;

use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;

interface Renderer
{
    public function render(PaymentInstruction $data, RenderStrategy $strategy): string;
}

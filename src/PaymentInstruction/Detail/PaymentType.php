<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

use Sco\BihuppQRCode\PaymentInstruction\Line;

enum PaymentType: string
{
    case Regular = 'D';
    case Urgent = 'N';

    public function toString(): string
    {
        return $this->value.Line::END;
    }
}

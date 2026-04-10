<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

enum PaymentPriority: string
{
    case Regular = 'D';
    case Urgent = 'N';
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Detail;

enum Priority: string
{
    case Regular = 'N';
    case Urgent = 'D';
}

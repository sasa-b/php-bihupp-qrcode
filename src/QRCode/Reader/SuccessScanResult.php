<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\Reader;

use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;
use Sco\BihuppQRCode\QRCode\ScanResult;

final readonly class SuccessScanResult implements ScanResult
{
    public function __construct(
        public PaymentInstruction $paymentInstruction,
        public string $rawPayload,
    ) {}
}

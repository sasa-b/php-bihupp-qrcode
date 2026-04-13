<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode\Reader;

use Sco\BihuppQRCode\QRCode\ScanResult;

final readonly class FailureScanResult implements ScanResult
{
    public function __construct(
        public \Throwable $error,
        public ?string $rawPayload,
    ) {}
}

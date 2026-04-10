<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

final readonly class EmptyLine extends Line
{
    public function __construct(
        public string $value = '',
    ) {}
}

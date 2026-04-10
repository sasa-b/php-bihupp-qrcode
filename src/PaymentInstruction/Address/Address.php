<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Address;

use Sco\BihuppQRCode\PaymentInstruction\Line;

final readonly class Address
{
    public function __construct(
        public AddressLine1 $addressLine1,
        public AddressLine2 $addressLine2,
    ) {}

    /**
     * @return array<Line>
     */
    public function toArray(): array
    {
        return [
            $this->addressLine1,
            $this->addressLine2,
        ];
    }
}

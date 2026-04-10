<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Recipient;

use Sco\BihuppQRCode\PaymentInstruction\Address\Address;
use Sco\BihuppQRCode\PaymentInstruction\Name;

/**
 * Primalac.
 */
final readonly class Recipient
{
    public function __construct(
        public Name $name,
        public Address $address,
        public RecipientAccount $account,
    ) {}
}

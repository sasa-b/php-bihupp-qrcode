<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Sender;

use Sco\BihuppQRCode\PaymentInstruction\Address\Address;
use Sco\BihuppQRCode\PaymentInstruction\Name;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\PhoneNumber;

/**
 * Pošijaoc.
 */
final readonly class Sender
{
    public function __construct(
        public Name $name,
        public Address $address,
        public SenderAccount $account,
        public ?PhoneNumber $phoneNumber = null,
    ) {}
}

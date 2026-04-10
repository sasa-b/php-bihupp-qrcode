<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Currency;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\Recipient;
use Sco\BihuppQRCode\PaymentInstruction\Sender\Sender;

/**
 * Naloga za plaćanje u bankama u unutrašnjem platnom promet
 * Ako pošiljaoc ne bude poslan, banke će povući podatke iz svog sistema i ispuniti automatski.
 */
final readonly class PaymentInstruction
{
    public function __construct(
        public ?Sender $sender,
        public Recipient $recipient,
        public PaymentPurpose $purpose,
        public ?PaymentReference $reference,
        public Amount $amount,
        public Currency $currency = new Currency(),
        public Version $version = new Version(),
    ) {}

    /**
     * @return array<Line>
     */
    public function toLines(): array
    {
        return [
            $this->version,

            $this->sender->name,
            $this->sender->address->addressLine1,
            $this->sender->address->addressLine2,
            $this->sender->phoneNumber,

            $this->purpose,
            $this->reference,

            $this->recipient->name,
            $this->recipient->address->addressLine1,
            $this->recipient->address->addressLine2,

            $this->sender->account,
            $this->recipient->account,

            $this->amount,
            $this->currency,
        ];
    }
}

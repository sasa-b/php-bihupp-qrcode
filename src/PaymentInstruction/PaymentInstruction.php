<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Currency;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPriorityLine;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\Recipient;
use Sco\BihuppQRCode\PaymentInstruction\Sender\Sender;

/**
 * Naloga za plaćanje u bankama u unutrašnjem platnom promet
 * Ako pošiljaoc ne bude poslan, banke će povući podatke iz svog sistema i ispuniti automatski.
 */
final readonly class PaymentInstruction implements \Stringable
{
    public function __construct(
        public ?Sender $sender,
        public Recipient $recipient,
        public PaymentPurpose $purpose,
        public ?PaymentReference $reference,
        public Amount $amount,
        public Currency $currency = new Currency(),
        public PaymentPriorityLine $paymentPriority = new PaymentPriorityLine(),
        public ?PublicRevenueInstruction $publicRevenue = null,
        public Version $version = new Version(),
    ) {}

    /**
     * @return array<Line>
     */
    public function lines(): array
    {
        // Order of these is important and should not change
        return [
            $this->version,

            $this->sender->name,
            $this->sender->address->addressLine1,
            $this->sender->address->addressLine2,
            $this->sender->phoneNumber ?: new EmptyLine(),

            $this->purpose,
            $this->reference ?: new EmptyLine(),

            $this->recipient->name,
            $this->recipient->address->addressLine1,
            $this->recipient->address->addressLine2,

            $this->sender->account,
            $this->recipient->account,

            $this->amount,
            $this->currency,
            $this->paymentPriority,

            ...[
                $this->publicRevenue?->senderTaxId ?: new EmptyLine(),
                $this->publicRevenue?->paymentType ?: new EmptyLine(),
                $this->publicRevenue?->revenueType ?: new EmptyLine(),
                $this->publicRevenue?->taxPeriodStartDate ?: new EmptyLine(),
                $this->publicRevenue?->taxPeriodEndDate ?: new EmptyLine(),
                $this->publicRevenue?->municipalCode ?: new EmptyLine(),
                $this->publicRevenue?->budgetCode ?: new EmptyLine(),
                $this->publicRevenue?->paymentReference ?: new EmptyLine(),
            ],
        ];
    }
    public function __toString(): string
    {
        return implode('', $this->lines());
    }
}

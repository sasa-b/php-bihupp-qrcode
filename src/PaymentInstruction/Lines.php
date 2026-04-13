<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

use Sco\BihuppQRCode\PaymentInstruction\Address\Address;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine1;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Currency;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPriority;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Sender;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\BudgetOrgCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\MunicipalCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentReference as PublicRevenuePaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\RevenueType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\SenderTaxId;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\TaxPeriodDate;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\PhoneNumber;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\Recipient;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;

/**
 * @implements \IteratorAggregate<int,Line>
 * @implements \ArrayAccess<int,Line|null>
 */
final readonly class Lines implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @var array<int,Line>
     */
    private array $lines;

    public function __construct(Line ...$line)
    {
        $this->lines = array_values($line);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->lines);
    }

    public function implode(): string
    {
        return implode('', $this->lines);
    }

    /**
     * @param int $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->lines[$offset]);
    }

    /**
     * @param int $offset
     */
    public function offsetGet(mixed $offset): ?Line
    {
        return $this->lines[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Noop
    }

    public function offsetUnset(mixed $offset): void
    {
        // Noop
    }

    public function count(): int
    {
        return count($this->lines);
    }

    public function toPaymentInstruction(): PaymentInstruction
    {
        if ($this->lines[6] instanceof PaymentReference) {
            $paymentReference = $this->lines[6]->value !== '' ? $this->lines[6] : null;
        } else {
            $paymentReference = null;
        }

        $noPublicRevenueInstructions = count(array_filter(array_slice($this->lines, 15, 22), static fn ($line) => $line instanceof EmptyLine)) === 8;

        return new PaymentInstruction(
            sender: new Sender(
                name: $this->lines[1] instanceof Name ? $this->lines[1] : new Name(''),
                address: new Address(
                    addressLine1: $this->lines[2] instanceof AddressLine1 ? $this->lines[2] : new AddressLine1(''),
                    addressLine2: $this->lines[3] instanceof AddressLine2 ? $this->lines[3] : new AddressLine2(''),
                ),
                account: $this->lines[10] instanceof Account ? $this->lines[10] : new Account(''),
                phoneNumber: $this->lines[4] instanceof PhoneNumber ? $this->lines[4] : null,
            ),
            recipient: new Recipient(
                name: $this->lines[7] instanceof Name ? $this->lines[7] : new Name(''),
                address: new Address(
                    addressLine1: $this->lines[8] instanceof AddressLine1 ? $this->lines[8] : new AddressLine1(''),
                    addressLine2: $this->lines[9] instanceof AddressLine2 ? $this->lines[9] : new AddressLine2(''),
                ),
                account: $this->lines[11] instanceof RecipientAccount ? $this->lines[11] : new RecipientAccount(new Account('')),
            ),
            purpose: $this->lines[5] instanceof PaymentPurpose ? $this->lines[5] : new PaymentPurpose(''),
            reference: $paymentReference,
            amount: $this->lines[12] instanceof Amount ? $this->lines[12] : new Amount('0'),
            currency: $this->lines[13] instanceof Currency ? $this->lines[13] : new Currency(),
            paymentPriority: $this->lines[14] instanceof PaymentPriority ? $this->lines[14] : PaymentPriority::regular(),
            publicRevenue: $noPublicRevenueInstructions ? null : new PublicRevenueInstruction(
                senderTaxId: $this->lines[15] instanceof SenderTaxId ? $this->lines[15] : SenderTaxId::empty(),
                paymentType: $this->lines[16] instanceof PaymentType ? $this->lines[16] : PaymentType::empty(),
                revenueType: $this->lines[17] instanceof RevenueType ? $this->lines[17] : RevenueType::empty(),
                taxPeriodStartDate: $this->lines[18] instanceof TaxPeriodDate ? $this->lines[18] : TaxPeriodDate::empty(),
                taxPeriodEndDate: $this->lines[19] instanceof TaxPeriodDate ? $this->lines[19] : TaxPeriodDate::empty(),
                municipalCode: $this->lines[20] instanceof MunicipalCode ? $this->lines[20] : MunicipalCode::empty(),
                budgetCode: $this->lines[21] instanceof BudgetOrgCode ? $this->lines[21] : BudgetOrgCode::empty(),
                paymentReference: $this->lines[22] instanceof PublicRevenuePaymentReference ? $this->lines[22] : PublicRevenuePaymentReference::empty(),
            ),
            version: $this->lines[0] instanceof Version ? $this->lines[0] : new Version(),
        );
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\BudgetOrgCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\MunicipalCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\RevenueType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\SenderTaxId;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\TaxPeriodDate;

/**
 * Samo za uplate javnih prihoda.
 */
final readonly class PublicRevenueInstruction
{
    public function __construct(
        public SenderTaxId $senderTaxId,
        public PaymentType $paymentType,
        public RevenueType $revenueType,
        public TaxPeriodDate $taxPeriodStartDate,
        public TaxPeriodDate $taxPeriodEndDate,
        public MunicipalCode $municipalCode,
        public BudgetOrgCode $budgetOrgCode,
        public PaymentReference $paymentReference,
    ) {}
}

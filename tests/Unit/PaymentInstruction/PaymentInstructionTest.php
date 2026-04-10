<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\EmptyLine;
use Sco\BihuppQRCode\PaymentInstruction\Name;
use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\BudgetOrgCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\MunicipalCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentReference as PublicRevenuePaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\RevenueType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\SenderTaxId;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\TaxPeriodDate;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenueInstruction;

final class PaymentInstructionTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_payment_instruction(): void
    {
        $instruction = new PaymentInstruction(
            sender: Fixtures::sender(),
            recipient: Fixtures::recipient(),
            purpose: new PaymentPurpose('Invoice payment'),
            reference: new PaymentReference('INV2024001'),
            amount: new Amount('10000'),
        );

        $this->assertSame('Invoice payment', $instruction->purpose->value);
        $this->assertSame('000000000010000', $instruction->amount->value);
        $this->assertSame("BAM\n", (string) $instruction->currency);
        $this->assertSame('D', $instruction->paymentPriority->value);
        $this->assertNull($instruction->publicRevenue);
    }

    #[Test]
    public function it_converts_to_string_with_all_lines_in_correct_order(): void
    {
        $instruction = new PaymentInstruction(
            sender: Fixtures::sender(),
            recipient: Fixtures::recipient(),
            purpose: new PaymentPurpose('Invoice payment'),
            reference: new PaymentReference('INV2024001'),
            amount: new Amount('10000'),
        );

        $expected = implode('', [
            "BIHUPP10\n",            // version
            "Marko Marković\n",      // sender name
            "Ulica Meše Selimovića 12\n", // sender address line 1
            "78000 Banja Luka\n",    // sender address line 2
            "\n",                    // sender phone (empty)
            "Invoice payment\n",     // purpose
            "INV2024001\n",          // reference
            "Pero Perić\n",          // recipient name
            "Titova 1\n",            // recipient address line 1
            "71000 Sarajevo\n",      // recipient address line 2
            "1234567890123456\n",    // sender account
            "9876543210987654\n",    // recipient account
            "000000000010000\n",     // amount
            "BAM\n",                 // currency
            "D\n",                   // payment priority
            "\n",                    // sender tax id (empty)
            "\n",                    // payment type (empty)
            "\n",                    // revenue type (empty)
            "\n",                    // tax period start date (empty)
            "\n",                    // tax period end date (empty)
            "\n",                    // municipal code (empty)
            "\n",                    // budget org code (empty)
            "\n",                    // payment reference (empty)
        ]);

        $this->assertSame($expected, (string) $instruction);
    }

    #[Test]
    public function it_converts_to_string_with_public_revenue_fields_in_correct_order(): void
    {
        $instruction = new PaymentInstruction(
            sender: Fixtures::sender(Name::business('Example Company d.o.o.')),
            recipient: Fixtures::recipient(new Name("Trezor")),
            purpose: new PaymentPurpose('Tax payment'),
            reference: null,
            amount: new Amount('500000'),
            publicRevenue: new PublicRevenueInstruction(
                senderTaxId: new SenderTaxId('0101990123456'),
                paymentType: new PaymentType('3'),
                revenueType: new RevenueType('712115'),
                taxPeriodStartDate: TaxPeriodDate::fromDate(new DateTimeImmutable('2024-01-01')),
                taxPeriodEndDate: TaxPeriodDate::fromDate(new DateTimeImmutable('2024-12-31')),
                municipalCode: new MunicipalCode('077'),
                budgetCode: new BudgetOrgCode('1200200'),
                paymentReference: new PublicRevenuePaymentReference('7110578163'),
            ),
        );

        $expected = implode('', [
            "BIHUPP10\n",            // version
            "Example Company d.o.o.\n",  // sender name
            "Ulica Meše Selimovića 12\n", // sender address line 1
            "78000 Banja Luka\n",    // sender address line 2
            "\n",                    // sender phone (empty)
            "Tax payment\n",         // purpose
            "\n",                    // reference (empty)
            "Trezor\n",              // recipient name
            "Titova 1\n",            // recipient address line 1
            "71000 Sarajevo\n",      // recipient address line 2
            "1234567890123456\n",    // sender account
            "9876543210987654\n",    // recipient account
            "000000000500000\n",     // amount
            "BAM\n",                 // currency
            "D\n",                   // payment priority
            "0101990123456\n",       // sender tax id (JMBG)
            "3\n",                   // payment type
            "712115\n",              // revenue type
            "01012024\n",            // tax period start date
            "31122024\n",            // tax period end date
            "077\n",                 // municipal code
            "1200200\n",             // budget org code
            "7110578163\n",          // payment reference
        ]);

        $this->assertSame($expected, (string) $instruction);
    }

    #[Test]
    public function it_replaces_reference_with_empty_line_when_not_provided(): void
    {
        $instruction = new PaymentInstruction(
            sender: Fixtures::sender(),
            recipient: Fixtures::recipient(),
            purpose: new PaymentPurpose('Invoice payment'),
            reference: null,
            amount: new Amount('10000'),
        );

        $lines = $instruction->lines();

        // Reference is line index 6 (0-based)
        $this->assertInstanceOf(EmptyLine::class, $lines[6]);
    }

    #[Test]
    public function it_includes_public_revenue_fields_in_output(): void
    {
        $publicRevenue = new PublicRevenueInstruction(
            senderTaxId: new SenderTaxId('0101990123456'),
            paymentType: new PaymentType('3'),
            revenueType: new RevenueType('712115'),
            taxPeriodStartDate: TaxPeriodDate::fromDate(new DateTimeImmutable('2024-01-01')),
            taxPeriodEndDate: TaxPeriodDate::fromDate(new DateTimeImmutable('2024-12-31')),
            municipalCode: new MunicipalCode('077'),
            budgetCode: new BudgetOrgCode('1200200'),
            paymentReference: new PublicRevenuePaymentReference('7110578163'),
        );

        $instruction = new PaymentInstruction(
            sender: Fixtures::sender(),
            recipient: Fixtures::recipient(),
            purpose: new PaymentPurpose('Tax payment'),
            reference: null,
            amount: new Amount('500000'),
            publicRevenue: $publicRevenue,
        );

        $output = (string) $instruction;

        $this->assertStringContainsString("0101990123456\n", $output);
        $this->assertStringContainsString("3\n", $output);
        $this->assertStringContainsString("712115\n", $output);
        $this->assertStringContainsString("01012024\n", $output);
        $this->assertStringContainsString("31122024\n", $output);
        $this->assertStringContainsString("077\n", $output);
        $this->assertStringContainsString("1200200\n", $output);
        $this->assertStringContainsString("7110578163\n", $output);
    }
}

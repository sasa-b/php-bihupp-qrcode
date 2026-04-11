<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Address\Address;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine1;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPriority;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Sender;
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
use Sco\BihuppQRCode\PaymentInstruction\Recipient\PhoneNumber;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\Recipient;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;

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
        $this->assertSame('N', $instruction->paymentPriority->value);
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
            "N\n",                   // payment priority
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
    public function it_converts_public_revenue_instruction_to_string_with_all_lines_in_correct_order(): void
    {
        $instruction = new PaymentInstruction(
            sender: Fixtures::sender(Name::business('Example Company d.o.o.')),
            recipient: Fixtures::recipient(new Name('Trezor')),
            purpose: new PaymentPurpose('Tax payment'),
            reference: null,
            amount: new Amount('500000'),
            publicRevenue: new PublicRevenueInstruction(
                senderTaxId: new SenderTaxId('0101990123456'),
                paymentType: new PaymentType('3'),
                revenueType: new RevenueType('712115'),
                taxPeriodStartDate: TaxPeriodDate::fromDate(new \DateTimeImmutable('2024-01-01')),
                taxPeriodEndDate: TaxPeriodDate::fromDate(new \DateTimeImmutable('2024-12-31')),
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
            "N\n",                   // payment priority
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
    public function it_converts_water_bill_example_with_sender_from_standard_doc_to_expected_string(): void
    {
        $instruction = new PaymentInstruction(
            sender: new Sender(
                name: Name::individual('DENISA', 'KOVAČEVIĆ-BATIĆ'),
                address: new Address(
                    addressLine1: AddressLine1::from('ŠARAJEVSKA ULICA', '43'),
                    addressLine2: AddressLine2::from('78000', 'BANJA LUKA'),
                ),
                account: new Account('1995320021237616'),
            ),
            recipient: new Recipient(
                name: Name::business('VODOVOD MOSTAR'),
                address: new Address(
                    addressLine1: AddressLine1::from('ALEKSE ŠANTIĆA', ''),
                    addressLine2: AddressLine2::from('88000', 'MOSTAR'),
                ),
                account: RecipientAccount::from(new Account('1010000236542719')),
            ),
            purpose: new PaymentPurpose('Troškovi vode za 6. mjesec'),
            reference: new PaymentReference('1445-26554-11222'),
            amount: new Amount('9862'),
            paymentPriority: PaymentPriority::urgent(),
        );

        $expected = implode('', [
            "BIHUPP10\n",                        // version
            "DENISA KOVAČEVIĆ-BATIĆ\n",          // sender name
            "ŠARAJEVSKA ULICA 43\n",             // sender address line 1
            "78000 BANJA LUKA\n",                // sender address line 2
            "\n",                                // sender phone (empty)
            "Troškovi vode za 6. mjesec\n",      // purpose
            "1445-26554-11222\n",                // reference
            "VODOVOD MOSTAR\n",                  // recipient name
            "ALEKSE ŠANTIĆA\n",                 // recipient address line 1
            "88000 MOSTAR\n",                    // recipient address line 2
            "1995320021237616\n",               // sender account
            "1010000236542719\n",               // recipient account
            "000000000009862\n",                // amount (9862 pennies = 98.62 BAM)
            "BAM\n",                             // currency
            "D\n",                               // payment priority (urgent)
            "\n",                                // sender tax id (empty)
            "\n",                                // payment type (empty)
            "\n",                                // revenue type (empty)
            "\n",                                // tax period start date (empty)
            "\n",                                // tax period end date (empty)
            "\n",                                // municipal code (empty)
            "\n",                                // budget org code (empty)
            "\n",                                // payment reference (empty)
        ]);

        $this->assertSame($expected, (string) $instruction);
    }

    #[Test]
    public function it_converts_water_bill_example_without_sender_from_standard_doc_to_expected_string(): void
    {
        $instruction = new PaymentInstruction(
            sender: new Sender(
                name: new Name(''),
                address: new Address(
                    addressLine1: AddressLine1::from('', ''),
                    addressLine2: AddressLine2::from('', ''),
                ),
                account: new Account('1995320021237616'),
            ),
            recipient: new Recipient(
                name: Name::business('VODOVOD MOSTAR'),
                address: new Address(
                    addressLine1: AddressLine1::from('ALEKSE ŠANTIĆA', ''),
                    addressLine2: AddressLine2::from('88000', 'MOSTAR'),
                ),
                account: RecipientAccount::from(new Account('1010000236542719')),
            ),
            purpose: new PaymentPurpose('Troškovi vode za 6. mjesec'),
            reference: new PaymentReference('1445-26554-11222'),
            amount: new Amount('9862'),
            paymentPriority: PaymentPriority::urgent(),
        );

        $expected = implode('', [
            "BIHUPP10\n",                        // version
            "\n",                                // sender name (empty — bank fills from logged-in user)
            "\n",                                // sender address line 1 (empty)
            "\n",                                // sender address line 2 (empty)
            "\n",                                // sender phone (empty)
            "Troškovi vode za 6. mjesec\n",      // purpose
            "1445-26554-11222\n",                // reference
            "VODOVOD MOSTAR\n",                  // recipient name
            "ALEKSE ŠANTIĆA\n",                 // recipient address line 1
            "88000 MOSTAR\n",                    // recipient address line 2
            "1995320021237616\n",               // sender account
            "1010000236542719\n",               // recipient account
            "000000000009862\n",                // amount (9862 pennies = 98.62 BAM)
            "BAM\n",                             // currency
            "D\n",                               // payment priority (urgent)
            "\n",                                // sender tax id (empty)
            "\n",                                // payment type (empty)
            "\n",                                // revenue type (empty)
            "\n",                                // tax period start date (empty)
            "\n",                                // tax period end date (empty)
            "\n",                                // municipal code (empty)
            "\n",                                // budget org code (empty)
            "\n",                                // payment reference (empty)
        ]);

        $this->assertSame($expected, (string) $instruction);
    }

    #[Test]
    public function it_converts_standard_doc_max_utf8_field_length_example_to_expected_string(): void
    {
        $maxName = str_repeat('Aa', 25);        // 50 chars
        $maxAddr1 = str_repeat('Aa', 25);       // 50 chars
        $maxAddr2 = str_repeat('Aa', 12).'A'; // 25 chars
        $maxPurpose = str_repeat('Aa', 55);     // 110 chars
        $maxReference = str_repeat('Aa', 15);   // 30 chars
        $recipientAccount = implode(',', array_fill(0, 20, '1234567890123456')); // 339 chars

        $instruction = new PaymentInstruction(
            sender: new Sender(
                name: new Name($maxName),
                address: new Address(
                    addressLine1: AddressLine1::from($maxAddr1, ''),
                    addressLine2: AddressLine2::from($maxAddr2, ''),
                ),
                account: new Account('1234567890123456'),
                phoneNumber: new PhoneNumber('+12345678901234'),
            ),
            recipient: new Recipient(
                name: Name::business($maxName),
                address: new Address(
                    addressLine1: AddressLine1::from($maxAddr1, ''),
                    addressLine2: AddressLine2::from($maxAddr2, ''),
                ),
                account: RecipientAccount::from(...array_fill(0, 20, new Account('1234567890123456'))),
            ),
            purpose: new PaymentPurpose($maxPurpose),
            reference: new PaymentReference($maxReference),
            amount: new Amount('123456789012345'),
            publicRevenue: new PublicRevenueInstruction(
                senderTaxId: new SenderTaxId('1234567890123'),
                paymentType: new PaymentType('0'),
                revenueType: new RevenueType('123456'),
                taxPeriodStartDate: TaxPeriodDate::fromDate(new \DateTimeImmutable('2023-05-12')),
                taxPeriodEndDate: TaxPeriodDate::fromDate(new \DateTimeImmutable('2024-05-12')),
                municipalCode: new MunicipalCode('123'),
                budgetCode: new BudgetOrgCode('1234567'),
                paymentReference: new PublicRevenuePaymentReference('1234567890'),
            ),
        );

        $expected = implode('', [
            "BIHUPP10\n",
            "$maxName\n",            // sender name (50 chars)
            "$maxAddr1\n",           // sender address line 1 (50 chars)
            "$maxAddr2\n",           // sender address line 2 (25 chars)
            "+12345678901234\n",     // sender phone (15 chars)
            "$maxPurpose\n",         // purpose (110 chars)
            "$maxReference\n",       // reference (30 chars)
            "$maxName\n",            // recipient name (50 chars)
            "$maxAddr1\n",           // recipient address line 1 (50 chars)
            "$maxAddr2\n",           // recipient address line 2 (25 chars)
            "1234567890123456\n",    // sender account (16 chars)
            "$recipientAccount\n",   // recipient account (20 × 16 + 19 commas = 339 chars)
            "123456789012345\n",     // amount (15 chars)
            "BAM\n",                 // currency
            "N\n",                   // payment priority (regular)
            "1234567890123\n",       // sender tax id (13 chars)
            "0\n",                   // payment type (1 char)
            "123456\n",              // revenue type (6 chars)
            "12052023\n",            // tax period start date (dmY of 2023-05-12)
            "12052024\n",            // tax period end date (dmY of 2024-05-12)
            "123\n",                 // municipal code (3 chars)
            "1234567\n",             // budget org code (7 chars)
            "1234567890\n",          // payment reference (10 digits)
        ]);

        $this->assertSame($expected, (string) $instruction);
    }

    #[Test]
    public function it_converts_standard_doc_max_ascii_field_length_example_to_expected_string(): void
    {
        $maxName = str_repeat('Aa', 25);        // 50 chars
        $maxAddr1 = str_repeat('Aa', 25);       // 50 chars
        $maxAddr2 = str_repeat('Aa', 12).'A';  // 25 chars
        $maxPurpose = str_repeat('Aa', 55);     // 110 chars
        $maxReference = str_repeat('Aa', 15);   // 30 chars
        $recipientAccount = implode(',', array_fill(0, 20, '1234567890123456')); // 339 chars

        // The standard document uses placeholder values that violate implementation constraints:
        //   - phone "123456789012345" has no "+" prefix (E.164 required) → "+12345678901234"
        //   - tax period dates "12345678" are not a valid calendar date → valid dates used below
        //   - public revenue payment reference "AaAaAaAaAa" contains letters → 10-digit "0987654321" used
        $instruction = new PaymentInstruction(
            sender: new Sender(
                name: new Name($maxName),
                address: new Address(
                    addressLine1: AddressLine1::from($maxAddr1, ''),
                    addressLine2: AddressLine2::from($maxAddr2, ''),
                ),
                account: new Account('1234567890123456'),
                phoneNumber: new PhoneNumber('+12345678901234'),
            ),
            recipient: new Recipient(
                name: Name::business($maxName),
                address: new Address(
                    addressLine1: AddressLine1::from($maxAddr1, ''),
                    addressLine2: AddressLine2::from($maxAddr2, ''),
                ),
                account: RecipientAccount::from(...array_fill(0, 20, new Account('1234567890123456'))),
            ),
            purpose: new PaymentPurpose($maxPurpose),
            reference: new PaymentReference($maxReference),
            amount: new Amount('123456789012345'),
            publicRevenue: new PublicRevenueInstruction(
                senderTaxId: new SenderTaxId('1234567890123'),
                paymentType: new PaymentType('0'),
                revenueType: new RevenueType('123456'),
                taxPeriodStartDate: TaxPeriodDate::fromDate(new \DateTimeImmutable('2045-06-01')),
                taxPeriodEndDate: TaxPeriodDate::fromDate(new \DateTimeImmutable('2099-12-31')),
                municipalCode: new MunicipalCode('123'),
                budgetCode: new BudgetOrgCode('1234567'),
                paymentReference: new PublicRevenuePaymentReference('0987654321'),
            ),
        );

        $expected = implode('', [
            "BIHUPP10\n",
            "$maxName\n",            // sender name (50 chars)
            "$maxAddr1\n",           // sender address line 1 (50 chars)
            "$maxAddr2\n",           // sender address line 2 (25 chars)
            "+12345678901234\n",     // sender phone (15 chars, E.164)
            "$maxPurpose\n",         // purpose (110 chars)
            "$maxReference\n",       // reference (30 chars)
            "$maxName\n",            // recipient name (50 chars)
            "$maxAddr1\n",           // recipient address line 1 (50 chars)
            "$maxAddr2\n",           // recipient address line 2 (25 chars)
            "1234567890123456\n",    // sender account (16 chars)
            "$recipientAccount\n",   // recipient account (20 × 16 + 19 commas = 339 chars)
            "123456789012345\n",     // amount (15 chars)
            "BAM\n",                 // currency
            "N\n",                   // payment priority (regular)
            "1234567890123\n",       // sender tax id (13 chars)
            "0\n",                   // payment type (1 char)
            "123456\n",              // revenue type (6 chars)
            "01062045\n",            // tax period start date (dmY of 2045-06-01)
            "31122099\n",            // tax period end date (dmY of 2099-12-31)
            "123\n",                 // municipal code (3 chars)
            "1234567\n",             // budget org code (7 chars)
            "0987654321\n",          // payment reference (10 digits)
        ]);

        $this->assertSame($expected, (string) $instruction);
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\PublicRevenue;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\TaxPeriodDate;

final class TaxPeriodDateTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_date(): void
    {
        $date = TaxPeriodDate::fromDate(new \DateTimeImmutable('2024-01-15'));

        $this->assertSame('15012024', $date->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $date = TaxPeriodDate::fromDate(new \DateTimeImmutable('2024-01-15'));

        $this->assertSame("15012024\n", (string) $date);
    }

    #[Test]
    public function it_formats_single_digit_day_and_month_with_leading_zeros(): void
    {
        $date = TaxPeriodDate::fromDate(new \DateTimeImmutable('2024-03-05'));

        $this->assertSame('05032024', $date->value);
    }

    #[Test]
    public function it_accepts_datetime_interface_implementations(): void
    {
        $mutable = new \DateTime('2024-12-31');
        $immutable = new \DateTimeImmutable('2024-12-31');

        $this->assertSame('31122024', TaxPeriodDate::fromDate($mutable)->value);
        $this->assertSame('31122024', TaxPeriodDate::fromDate($immutable)->value);
    }
}

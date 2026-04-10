<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\PublicRevenue;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\RevenueType;

final class RevenueTypeTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_revenue_code(): void
    {
        $type = new RevenueType('712115');

        $this->assertSame('712115', $type->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $type = new RevenueType('712115');

        $this->assertSame("712115\n", (string) $type);
    }

    #[Test]
    public function it_throws_exception_when_value_is_fewer_than_six_digits(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Invalid revenue type, must be 6 digits');

        new RevenueType('71211');
    }

    #[Test]
    public function it_throws_exception_when_value_is_more_than_six_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new RevenueType('7121150');
    }

    #[Test]
    public function it_throws_exception_when_value_contains_non_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new RevenueType('71211A');
    }

    #[Test]
    public function it_throws_exception_when_value_is_empty(): void
    {
        $this->expectException(InvalidValueException::class);

        new RevenueType('');
    }
}

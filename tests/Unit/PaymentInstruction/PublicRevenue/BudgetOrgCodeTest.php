<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\PublicRevenue;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\BudgetOrgCode;

final class BudgetOrgCodeTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_code(): void
    {
        $code = new BudgetOrgCode('1200200');

        $this->assertSame('1200200', $code->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $code = new BudgetOrgCode('1200200');

        $this->assertSame("1200200\n", (string) $code);
    }

    #[Test]
    public function it_throws_exception_when_value_is_not_seven_digits(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Budget organization code must be a 7 digits integer');

        new BudgetOrgCode('12345678');
    }

    #[Test]
    public function it_throws_exception_when_value_is_fewer_than_seven_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new BudgetOrgCode('120020');
    }

    #[Test]
    public function it_throws_exception_when_value_contains_non_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new BudgetOrgCode('120@200');
    }
}

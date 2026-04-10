<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\PublicRevenue;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentType;

final class PaymentTypeTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_payment_type(): void
    {
        $type = new PaymentType('3');

        $this->assertSame('3', $type->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $type = new PaymentType('3');

        $this->assertSame("3\n", (string) $type);
    }

    #[Test]
    public function it_throws_exception_when_value_is_not_a_digit(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Invalid payment type, must be a one digit number');

        new PaymentType('A');
    }

    #[Test]
    public function it_throws_exception_when_value_is_longer_than_one_digit(): void
    {
        $this->expectException(InvalidValueException::class);

        new PaymentType('12');
    }

    #[Test]
    public function it_throws_exception_when_value_is_empty(): void
    {
        $this->expectException(InvalidValueException::class);

        new PaymentType('');
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

final class AmountTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_amount(): void
    {
        $amount = new Amount('100.00');

        $this->assertSame('100.00', $amount->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $amount = new Amount('100.00');

        $this->assertSame("100.00\n", (string) $amount);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);

        // Create an amount string longer than 15 characters
        new Amount(str_repeat('9', 16));
    }

    #[Test]
    public function it_accepts_amount_at_max_length(): void
    {
        // 15 characters (e.g., 999999999999.99)
        $amount = new Amount('999999999999.99');

        $this->assertSame(15, strlen($amount->value));
    }

    #[Test]
    public function it_accepts_integer_amounts(): void
    {
        $amount = new Amount('100');

        $this->assertSame('100', $amount->value);
    }

    #[Test]
    public function it_accepts_decimal_amounts(): void
    {
        $amount = new Amount('123.45');

        $this->assertSame('123.45', $amount->value);
    }
}

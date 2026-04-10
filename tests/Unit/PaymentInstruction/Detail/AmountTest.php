<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidFormatException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

final class AmountTest extends TestCase
{
    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $amount = new Amount('10000');

        $this->assertSame("000000000010000\n", (string) $amount);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);

        // 16 digits, no dot to strip — exceeds max of 15
        new Amount(str_repeat('9', 16));
    }

    #[Test]
    public function it_accepts_amount_at_max_length(): void
    {
        $amount = new Amount(str_repeat('9', 15));

        $this->assertSame(15, strlen($amount->value));
    }

    #[Test]
    public function it_accepts_integer_amounts(): void
    {
        $amount = new Amount('100');

        $this->assertSame('000000000000100', $amount->value);
    }

    #[Test]
    public function it_accepts_decimal_amounts(): void
    {
        $amount = new Amount('100.00');

        $this->assertSame('000000000010000', $amount->value);
    }

    #[Test]
    public function it_creates_from_int(): void
    {
        $amount = Amount::fromInt(10000);

        $this->assertSame('000000000010000', $amount->value);
    }

    #[Test]
    public function it_creates_from_float(): void
    {
        $amount = Amount::fromFloat(123.45);

        $this->assertSame('000000000012345', $amount->value);
    }

    #[Test]
    public function it_throws_exception_when_invalid_characters_are_provided(): void
    {
        $this->expectException(InvalidFormatException::class);

        new Amount('100@00');
    }
}

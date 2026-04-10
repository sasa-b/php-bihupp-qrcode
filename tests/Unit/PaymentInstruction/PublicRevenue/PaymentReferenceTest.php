<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\PublicRevenue;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentReference;

final class PaymentReferenceTest extends TestCase
{
    public function it_creates_with_valid_reference(): void
    {
        $reference = new PaymentReference('7110578163');

        $this->assertSame('7110578163', $reference->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $reference = new PaymentReference('7110578163');

        $this->assertSame("7110578163\n", (string) $reference);
    }

    #[Test]
    public function it_throws_exception_when_value_is_not_ten_digits(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Payment reference must be a 10 digit integer.');

        new PaymentReference('12345678901');
    }

    #[Test]
    public function it_throws_exception_when_value_is_fewer_than_ten_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new PaymentReference('711057816');
    }

    #[Test]
    public function it_throws_exception_when_value_contains_non_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new PaymentReference('REF@123456');
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\PublicRevenue;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\SenderTaxId;

final class SenderTaxIdTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_jmbg(): void
    {
        $taxId = new SenderTaxId('0101990123456');

        $this->assertSame('0101990123456', $taxId->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $taxId = new SenderTaxId('0101990123456');

        $this->assertSame("0101990123456\n", (string) $taxId);
    }

    #[Test]
    public function it_throws_exception_when_value_is_fewer_than_thirteen_digits(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Invalid tax ID, must be 13 digits');

        new SenderTaxId('010199012345');
    }

    #[Test]
    public function it_throws_exception_when_value_is_more_than_thirteen_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new SenderTaxId('01019901234567');
    }

    #[Test]
    public function it_throws_exception_when_value_contains_non_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new SenderTaxId('010199012345A');
    }

    #[Test]
    public function it_throws_exception_when_value_is_empty(): void
    {
        $this->expectException(InvalidValueException::class);

        new SenderTaxId('');
    }
}

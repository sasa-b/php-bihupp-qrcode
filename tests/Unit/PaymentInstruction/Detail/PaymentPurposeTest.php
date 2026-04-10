<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

final class PaymentPurposeTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_purpose(): void
    {
        $purpose = new PaymentPurpose('Invoice payment');

        $this->assertSame('Invoice payment', $purpose->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $purpose = new PaymentPurpose('Invoice payment');

        $this->assertSame("Invoice payment\n", (string) $purpose);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('Payment purpose exceeds maximum length of 110 characters');

        // Create a purpose string longer than 110 characters
        new PaymentPurpose(str_repeat('a', 111));
    }

    #[Test]
    public function it_accepts_purpose_at_max_length(): void
    {
        // 110 characters
        $purpose = new PaymentPurpose(str_repeat('a', 110));

        $this->assertSame(110, strlen($purpose->value));
    }

    #[Test]
    public function it_accepts_special_characters(): void
    {
        $purpose = new PaymentPurpose('Uplata za usluge');

        $this->assertSame('Uplata za usluge', $purpose->value);
    }

    #[Test]
    public function it_accepts_allowed_special_characters(): void
    {
        $purpose = new PaymentPurpose('Payment (invoice #123) - 50% discount');

        $this->assertSame('Payment (invoice #123) - 50% discount', $purpose->value);
    }
}

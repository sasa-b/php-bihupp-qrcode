<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

final class PaymentReferenceTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_reference(): void
    {
        $reference = new PaymentReference('123456789');

        $this->assertSame('123456789', $reference->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $reference = new PaymentReference('123456789');

        $this->assertSame("123456789\n", (string) $reference);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('PaymentReference exceeds maximum length of 30 characters');

        // Create a reference string longer than 30 characters
        new PaymentReference(str_repeat('1', 31));
    }

    #[Test]
    public function it_accepts_reference_at_max_length(): void
    {
        // 30 characters
        $reference = new PaymentReference(str_repeat('1', 30));

        $this->assertSame(30, strlen($reference->value));
    }

    #[Test]
    public function it_accepts_alphanumeric_references(): void
    {
        $reference = new PaymentReference('INV2024-001');

        $this->assertSame('INV2024-001', $reference->value);
    }

    #[Test]
    public function it_accepts_allowed_special_characters(): void
    {
        $reference = new PaymentReference('REF-123/456');

        $this->assertSame('REF-123/456', $reference->value);
    }

    #[Test]
    public function it_throws_exception_when_invalid_characters_are_provided(): void
    {
        $this->expectException(InvalidCharacterException::class);

        new PaymentReference('REF@123');
    }
}

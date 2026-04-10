<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Recipient;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\PhoneNumber;

final class PhoneNumberTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_e164_format(): void
    {
        $phoneNumber = new PhoneNumber('+38761234567');

        $this->assertSame('+38761234567', $phoneNumber->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $phoneNumber = new PhoneNumber('+38761234567');

        $this->assertSame("+38761234567\n", (string) $phoneNumber);
    }

    #[Test]
    public function it_throws_exception_when_not_starting_with_plus(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Phone number must be in E.164 format starting with +.');

        new PhoneNumber('38761234567');
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('PhoneNumber exceeds maximum length of 15 characters');

        // Create a phone number longer than 15 characters (including +)
        new PhoneNumber('+'.str_repeat('1', 15));
    }

    #[Test]
    public function it_accepts_phone_number_at_max_length(): void
    {
        // 15 characters including the +
        $phoneNumber = new PhoneNumber('+'.str_repeat('1', 14));

        $this->assertSame(15, strlen($phoneNumber->value));
    }

    #[Test]
    public function it_accepts_various_country_codes(): void
    {
        $phoneNumbers = [
            '+1234567890',      // US
            '+441234567890',    // UK
            '+38761234567',     // Bosnia
            '+385912345678',    // Croatia
            '+381601234567',    // Serbia
        ];

        foreach ($phoneNumbers as $number) {
            $phoneNumber = new PhoneNumber($number);
            $this->assertSame($number, $phoneNumber->value);
        }
    }

    #[Test]
    public function it_rejects_phone_number_with_spaces(): void
    {
        $this->expectException(InvalidValueException::class);

        new PhoneNumber('387 61 234 567');
    }

    #[Test]
    public function it_rejects_phone_number_with_leading_zeros(): void
    {
        $this->expectException(InvalidValueException::class);

        new PhoneNumber('0038761234567');
    }

    #[Test]
    public function it_throws_exception_when_invalid_characters_are_provided(): void
    {
        $this->expectException(InvalidCharacterException::class);

        new PhoneNumber('+3876@234567');
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Address;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

final class AddressLine2Test extends TestCase
{
    #[Test]
    public function it_creates_with_valid_postal_code_and_city(): void
    {
        $address = AddressLine2::from('78000', 'Banja Luka');

        $this->assertSame('78000 Banja Luka', $address->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $addressLine2 = AddressLine2::from('78000', 'Banja Luka');

        $this->assertSame("78000 Banja Luka\n", (string) $addressLine2);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('AddressLine2 exceeds maximum length of 25 characters');

        // Create a string longer than 25 characters
        AddressLine2::from('78000', str_repeat('a', 30));
    }

    #[Test]
    public function it_accepts_string_at_max_length(): void
    {
        $postcode = '78000';
        $town = str_repeat('a', 19); // 71000 + space + 19 = 25 characters

        $addressLine2 = AddressLine2::from($postcode, $town);

        $this->assertSame($postcode.' '.$town, $addressLine2->value);
    }

    #[Test]
    public function it_throws_exception_when_invalid_characters_are_provided(): void
    {
        $this->expectException(InvalidCharacterException::class);

        AddressLine2::from('71000', 'Sarajevo@City');
    }
}

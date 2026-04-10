<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Address;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

final class AddressLine2Test extends TestCase
{
    #[Test]
    public function it_creates_from_postcode_and_town(): void
    {
        $addressLine2 = AddressLine2::from('71000', 'Sarajevo');

        $this->assertSame('71000 Sarajevo', $addressLine2->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $addressLine2 = AddressLine2::from('71000', 'Sarajevo');

        $this->assertSame("71000 Sarajevo\n", (string) $addressLine2);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('Postal code and city exceeds maximum length of 25 characters');

        // Create a string longer than 25 characters
        AddressLine2::from('71000', str_repeat('a', 30));
    }

    #[Test]
    public function it_accepts_string_at_max_length(): void
    {
        $postcode = '71000';
        $town = str_repeat('a', 19); // 71000 + space + 19 = 25 characters

        $addressLine2 = AddressLine2::from($postcode, $town);

        $this->assertSame($postcode.' '.$town, $addressLine2->value);
    }

    #[Test]
    public function it_accepts_special_characters(): void
    {
        $addressLine2 = AddressLine2::from('10000', 'Zagreb');

        $this->assertSame('10000 Zagreb', $addressLine2->value);
    }
}

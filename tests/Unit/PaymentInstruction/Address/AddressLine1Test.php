<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Address;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine1;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

final class AddressLine1Test extends TestCase
{
    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $addressLine1 = AddressLine1::from('Gospodska ulica', '123');

        $this->assertSame("Gospodska ulica 123\n", (string) $addressLine1);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('Street and number exceeds maximum length of 50 characters');

        // Create a string longer than 50 characters
        AddressLine1::from(str_repeat('a', 50), 'b');
    }

    #[Test]
    public function it_accepts_string_at_max_length(): void
    {
        $street = str_repeat('a', 45);
        $number = '123';

        $addressLine1 = AddressLine1::from($street, $number);

        $this->assertSame($street.' '.$number, $addressLine1->value);
    }

    #[Test]
    public function it_accepts_special_characters(): void
    {
        $addressLine1 = AddressLine1::from('Ulica Kralja Petra 1. Karađorđevića', '3');

        $this->assertSame('Ulica Kralja Petra 1. Karađorđevića 3', $addressLine1->value);
    }
}

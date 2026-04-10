<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Address;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Address\Address;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine1;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;

final class AddressTest extends TestCase
{
    #[Test]
    public function it_creates_with_address_lines(): void
    {
        $addressLine1 = AddressLine1::from('Gospodska ulica', '123');
        $addressLine2 = AddressLine2::from('71000', 'Sarajevo');

        $address = new Address($addressLine1, $addressLine2);

        $this->assertSame($addressLine1, $address->addressLine1);
        $this->assertSame($addressLine2, $address->addressLine2);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $addressLine1 = AddressLine1::from('Gospodska ulica', '123');
        $addressLine2 = AddressLine2::from('71000', 'Sarajevo');

        $address = new Address($addressLine1, $addressLine2);
        $array = $address->toArray();

        $this->assertCount(2, $array);
        $this->assertSame($addressLine1, $array[0]);
        $this->assertSame($addressLine2, $array[1]);
    }
}

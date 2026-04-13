<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction;

use Sco\BihuppQRCode\PaymentInstruction\Address\Address;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine1;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Sender;
use Sco\BihuppQRCode\PaymentInstruction\Name;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\Recipient;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;

final class Fixtures
{
    public static function sender(?Name $name = null): Sender
    {
        return new Sender(
            name: $name ?? Name::individual('Marko', 'Marković'),
            address: new Address(
                addressLine1: AddressLine1::from('Ulica Meše Selimovića', '12'),
                addressLine2: AddressLine2::from('78000', 'Banja Luka'),
            ),
            account: new Account('1234567890123456'),
        );
    }

    public static function recipient(?Name $name = null): Recipient
    {
        return new Recipient(
            name: $name ?? Name::individual('Pero', 'Perić'),
            address: new Address(
                addressLine1: AddressLine1::from('Titova', '1'),
                addressLine2: AddressLine2::from('71000', 'Sarajevo'),
            ),
            account: new RecipientAccount(new Account('9876543210987654')),
        );
    }
}

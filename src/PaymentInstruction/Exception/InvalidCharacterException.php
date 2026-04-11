<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\Exception;

use Sco\BihuppQRCode\BihuppQRCodeException;

final class InvalidCharacterException extends BihuppQRCodeException
{
    public function __construct(string $line)
    {
        parent::__construct("$line contains invalid characters, allowed characters are: 0-9, a-z, A-Z, č, ć, đ, š, ž, Č,Ć,Đ,Š,Ž,,:.?-()+'/");
    }
}

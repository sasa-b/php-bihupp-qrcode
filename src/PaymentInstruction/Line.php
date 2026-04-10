<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine1;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidFormatException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\PhoneNumber;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;
use Sco\BihuppQRCode\PaymentInstruction\Sender\SenderAccount;

abstract readonly class Line implements \Stringable
{
    public const string END = "\n";

    private const string ALLOWED_CHARS_REGEXP = "#[^0-9a-zA-ZčćđšžČĆĐŠŽ ,:.?()+'/-]#u";

    public string $value;

    public function toString(): string
    {
        return $this->value.self::END;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @throws InvalidLengthException
     * @throws InvalidFormatException
     * @throws InvalidCharacterException
     */
    protected static function validate(string $class, string $value, int $maxLength): void
    {
        $length = strlen($value);

        $line = match ($class) {
            AddressLine1::class => 'Street and number',
            AddressLine2::class => 'Postal code and city',
            PaymentPurpose::class => 'Payment purpose',
            PaymentReference::class => 'Payment reference',
            PhoneNumber::class => 'Phone number',
            SenderAccount::class => 'Sender account',
            RecipientAccount::class => 'Recipient account',
            default => (static function (string $fqcn) {
                $segments = explode('\\', $fqcn);

                return end($segments);
            })($class),
        };

        if ($length > $maxLength) {
            throw new InvalidLengthException($line, $maxLength, $length);
        }

        if (preg_match(self::ALLOWED_CHARS_REGEXP, $value) === 1) {
            throw new InvalidCharacterException($line);
        }
    }
}

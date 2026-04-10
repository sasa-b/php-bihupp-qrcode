<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

/**
 * Naziv/Ime i prezime (uplatioca ili primaoca).
 */
final readonly class Name extends Line
{
    private const int MAX_LENGTH = 50;

    /**
     * @throws InvalidLengthException
     */
    private function __construct(
        public string $value,
    ) {
        self::validate(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function individual(string $firstName, string $lastName): self
    {
        return new self("$firstName $lastName");
    }

    public function business($name): self
    {
        return new self($name);
    }
}

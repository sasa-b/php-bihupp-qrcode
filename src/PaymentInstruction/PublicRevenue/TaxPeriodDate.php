<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction\PublicRevenue;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Line;

final readonly class TaxPeriodDate extends Line
{
    public const int MAX_LENGTH = 8;

    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     */
    private function __construct(public string $value)
    {
        self::validateLengthAndChars(__CLASS__, $value, self::MAX_LENGTH);
    }

    public static function fromDate(\DateTimeInterface $date): self
    {
        return new self($date->format('dmY'));
    }

    public static function empty(): self
    {
        return new self('');
    }

    public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('dmY', $this->value);
    }
}

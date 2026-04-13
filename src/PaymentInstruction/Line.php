<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\PaymentInstruction;

use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;

abstract readonly class Line implements \Stringable
{
    public const string END = "\n";

    private const string ALLOWED_CHARS_REGEXP = "#[^0-9a-zA-ZčćđšžČĆĐŠŽ ,:.?()+'/-]#u";

    /** @phpstan-ignore-next-line */
    public string $value;

    public function __toString(): string
    {
        return $this->value.self::END;
    }

    /**
     * @throws InvalidLengthException
     * @throws InvalidValueException
     * @throws InvalidCharacterException
     */
    protected static function validateLengthAndChars(string $class, string $value, int $maxLength, int $minLength = 0): void
    {
        $length = strlen($value);

        $line = (static function (string $fqcn) {
            $segments = explode('\\', $fqcn);

            return end($segments);
        })($class);

        if ($length > $maxLength) {
            throw InvalidLengthException::max($line, $maxLength, $length);
        }

        if ($length < $minLength) {
            throw InvalidLengthException::min($line, $minLength, $length);
        }

        if (preg_match(self::ALLOWED_CHARS_REGEXP, $value) === 1) {
            throw new InvalidCharacterException($line);
        }
    }
}

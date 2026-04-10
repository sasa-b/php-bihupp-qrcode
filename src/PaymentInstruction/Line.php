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
    protected static function validateLengthAndChars(string $class, string $value, int $maxLength): void
    {
        $length = strlen($value);

        $line = (static function (string $fqcn) {
            $segments = explode('\\', $fqcn);

            return end($segments);
        })($class);

        if ($length > $maxLength) {
            throw new InvalidLengthException($line, $maxLength, $length);
        }

        if (preg_match(self::ALLOWED_CHARS_REGEXP, $value) === 1) {
            throw new InvalidCharacterException($line);
        }
    }
}

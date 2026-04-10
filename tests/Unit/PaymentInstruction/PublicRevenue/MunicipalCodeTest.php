<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\PublicRevenue;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\MunicipalCode;

final class MunicipalCodeTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_code(): void
    {
        $code = new MunicipalCode('077');

        $this->assertSame('077', $code->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $code = new MunicipalCode('077');

        $this->assertSame("077\n", (string) $code);
    }

    #[Test]
    public function it_throws_exception_when_value_is_not_three_digits(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Invalid municipal code must be a 3 digit integer');

        new MunicipalCode('1234');
    }

    #[Test]
    public function it_throws_exception_when_value_is_fewer_than_three_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new MunicipalCode('07');
    }

    #[Test]
    public function it_throws_exception_when_value_contains_non_digits(): void
    {
        $this->expectException(InvalidValueException::class);

        new MunicipalCode('07@');
    }
}

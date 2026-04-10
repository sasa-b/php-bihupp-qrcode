<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Currency;

final class CurrencyTest extends TestCase
{
    #[Test]
    public function it_uses_default_currency(): void
    {
        $currency = new Currency();

        $this->assertSame('BAM', $currency->value);
    }

    #[Test]
    public function it_accepts_custom_currency(): void
    {
        $currency = new Currency('EUR');

        $this->assertSame('EUR', $currency->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $currency = new Currency('USD');

        $this->assertSame("USD\n", (string) $currency);
    }

    #[Test]
    public function it_accepts_three_letter_currency_codes(): void
    {
        $currencies = ['BAM', 'EUR', 'USD', 'GBP', 'JPY'];

        foreach ($currencies as $code) {
            $currency = new Currency($code);
            $this->assertSame($code, $currency->value);
        }
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Currency;

final class CurrencyTest extends TestCase
{
    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $currency = new Currency();

        $this->assertSame("BAM\n", (string) $currency);
    }
}

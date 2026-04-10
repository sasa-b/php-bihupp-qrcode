<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentType;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentTypeLine;

final class PaymentTypeLineTest extends TestCase
{
    #[Test]
    public function it_creates_from_regular_payment_type(): void
    {
        $paymentTypeLine = PaymentTypeLine::from(PaymentType::Regular);

        $this->assertSame('D', $paymentTypeLine->value);
    }

    #[Test]
    public function it_creates_from_urgent_payment_type(): void
    {
        $paymentTypeLine = PaymentTypeLine::from(PaymentType::Urgent);

        $this->assertSame('N', $paymentTypeLine->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $paymentTypeLine = PaymentTypeLine::from(PaymentType::Urgent);

        $this->assertSame("N\n", (string) $paymentTypeLine);
    }
}

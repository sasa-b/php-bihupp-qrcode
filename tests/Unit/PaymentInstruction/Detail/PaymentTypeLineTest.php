<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPriority;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPriorityLine;

final class PaymentTypeLineTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_default_priority(): void
    {
        $line = new PaymentPriorityLine();

        $this->assertSame('D', $line->value);
    }

    #[Test]
    public function it_creates_from_regular_payment_type(): void
    {
        $paymentTypeLine = PaymentPriorityLine::from(PaymentPriority::Regular);

        $this->assertSame('D', $paymentTypeLine->value);
    }

    #[Test]
    public function it_creates_from_urgent_payment_type(): void
    {
        $paymentTypeLine = PaymentPriorityLine::from(PaymentPriority::Urgent);

        $this->assertSame('N', $paymentTypeLine->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $paymentTypeLine = PaymentPriorityLine::from(PaymentPriority::Urgent);

        $this->assertSame("N\n", (string) $paymentTypeLine);
    }
}

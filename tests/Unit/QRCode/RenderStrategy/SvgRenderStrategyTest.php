<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\QRCode\RenderStrategy;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;
use Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Fixtures;

class SvgRenderStrategyTest extends TestCase
{
    #[Test]
    public function it_can_convert_to_svg_qrcode_by_default(): void
    {
        $instruction = new PaymentInstruction(
            sender: Fixtures::sender(),
            recipient: Fixtures::recipient(),
            purpose: new PaymentPurpose('Invoice payment'),
            reference: new PaymentReference('INV2024001'),
            amount: new Amount('10000'),
        );

        $qrCode = $instruction->toQRCode();

        $this->assertNotEmpty($qrCode);

        $this->assertStringContainsString(<<<SVG
        <?xml version="1.0" encoding="UTF-8"?>
        <svg xmlns="http://www.w3.org/2000/svg" 
        SVG, $qrCode);
    }
}

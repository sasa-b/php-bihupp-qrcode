<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\QRCode\RenderStrategy;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;
use Sco\BihuppQRCode\QRCode\RenderStrategy\ImagickJpeg;
use Sco\BihuppQRCode\QRCode\RenderStrategy\ImagickPng;
use Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Fixtures;

final class ImagickRenderStrategyTest extends TestCase
{
    private PaymentInstruction $instruction;

    protected function setUp(): void
    {
        if (!extension_loaded('imagick')) {
            $this->markTestSkipped('Imagick extension is not loaded.');
        }

        $this->instruction = new PaymentInstruction(
            sender: Fixtures::sender(),
            recipient: Fixtures::recipient(),
            purpose: new PaymentPurpose('Invoice payment'),
            reference: new PaymentReference('INV2024001'),
            amount: new Amount('10000'),
        );
    }

    #[Test]
    public function it_renders_png_qrcode_using_imagick(): void
    {
        $output = $this->instruction->toQRCode(renderStrategy: new ImagickPng());

        $this->assertNotEmpty($output);
        // PNG magic bytes: \x89 P N G \r \n \x1a \n
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $output);
    }

    #[Test]
    public function it_renders_jpeg_qrcode_using_imagick(): void
    {
        $output = $this->instruction->toQRCode(renderStrategy: new ImagickJpeg());

        $this->assertNotEmpty($output);
        // JPEG magic bytes: \xff \xd8 \xff
        $this->assertStringStartsWith("\xff\xd8\xff", $output);
    }
}

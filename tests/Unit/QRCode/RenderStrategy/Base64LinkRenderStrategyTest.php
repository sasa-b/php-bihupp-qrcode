<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\QRCode\RenderStrategy;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;
use Sco\BihuppQRCode\QRCode\RenderStrategy\Base64Link;
use Sco\BihuppQRCode\QRCode\RenderStrategy\GDJpeg;
use Sco\BihuppQRCode\QRCode\RenderStrategy\GDPng;
use Sco\BihuppQRCode\QRCode\RenderStrategy\ImagickJpeg;
use Sco\BihuppQRCode\QRCode\RenderStrategy\ImagickPng;
use Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Fixtures;

final class Base64LinkRenderStrategyTest extends TestCase
{
    private PaymentInstruction $instruction;

    protected function setUp(): void
    {
        $this->instruction = new PaymentInstruction(
            sender: Fixtures::sender(),
            recipient: Fixtures::recipient(),
            purpose: new PaymentPurpose('Invoice payment'),
            reference: new PaymentReference('INV2024001'),
            amount: new Amount('10000'),
        );
    }

    #[Test]
    public function it_renders_svg_as_base64_data_uri_by_default(): void
    {
        $output = $this->instruction->toQRCode(renderStrategy: new Base64Link());

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $output);
    }

    #[Test]
    public function it_renders_gd_png_as_base64_data_uri(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded.');
        }

        $output = $this->instruction->toQRCode(renderStrategy: new Base64Link(new GDPng()));

        $this->assertStringStartsWith('data:image/png;base64,', $output);
    }

    #[Test]
    public function it_renders_gd_jpeg_as_base64_data_uri(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded.');
        }

        $output = $this->instruction->toQRCode(renderStrategy: new Base64Link(new GDJpeg()));

        $this->assertStringStartsWith('data:image/jpeg;base64,', $output);
    }

    #[Test]
    public function it_renders_imagick_png_as_base64_data_uri(): void
    {
        if (!extension_loaded('imagick')) {
            $this->markTestSkipped('Imagick extension is not loaded.');
        }

        $output = $this->instruction->toQRCode(renderStrategy: new Base64Link(new ImagickPng()));

        $this->assertStringStartsWith('data:image/png;base64,', $output);
    }

    #[Test]
    public function it_renders_imagick_jpeg_as_base64_data_uri(): void
    {
        if (!extension_loaded('imagick')) {
            $this->markTestSkipped('Imagick extension is not loaded.');
        }

        $output = $this->instruction->toQRCode(renderStrategy: new Base64Link(new ImagickJpeg()));

        $this->assertStringStartsWith('data:image/jpeg;base64,', $output);
    }
}

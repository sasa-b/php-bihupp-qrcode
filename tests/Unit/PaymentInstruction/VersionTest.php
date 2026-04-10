<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Version;

final class VersionTest extends TestCase
{
    #[Test]
    public function it_uses_default_version(): void
    {
        $version = new Version();

        $this->assertSame('BIHUPP10', $version->value);
    }

    #[Test]
    public function it_accepts_custom_version(): void
    {
        $version = new Version('BIHUPP20');

        $this->assertSame('BIHUPP20', $version->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char_with_newline(): void
    {
        $version = new Version();

        $this->assertSame("BIHUPP10\n", (string) $version);
    }
}

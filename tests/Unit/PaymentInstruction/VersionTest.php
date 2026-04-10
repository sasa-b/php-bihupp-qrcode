<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Version;

final class VersionTest extends TestCase
{
    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $version = new Version();

        $this->assertSame("BIHUPP10\n", (string) $version);
    }
}

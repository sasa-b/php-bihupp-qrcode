<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Detail;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Name;

final class NameTest extends TestCase
{
    #[Test]
    public function it_creates_individual_name(): void
    {
        $name = Name::individual('Marko', 'Marković');

        $this->assertSame('Marko Marković', $name->value);
    }

    #[Test]
    public function it_creates_business_name(): void
    {
        $name = Name::individual('', '');
        $businessName = $name->business('ACME Corporation');

        $this->assertSame('ACME Corporation', $businessName->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $name = Name::individual('Marko', 'Marković');

        $this->assertSame("Marko Marković\n", (string) $name);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);

        // Create a name longer than 50 characters
        Name::individual(str_repeat('a', 30), str_repeat('b', 30));
    }

    #[Test]
    public function it_accepts_name_at_max_length(): void
    {
        // 50 characters total
        $name = Name::individual(str_repeat('a', 24), str_repeat('b', 25));

        $this->assertSame(50, strlen($name->value));
    }

    #[Test]
    public function it_throws_exception_when_invalid_characters_are_provided(): void
    {
        $this->expectException(InvalidCharacterException::class);

        Name::individual('Invalid@Name', 'Test');
    }
}

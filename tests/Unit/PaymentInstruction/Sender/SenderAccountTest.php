<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Sender;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;

final class SenderAccountTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_account(): void
    {
        $account = new Account('1234567890123456');

        $this->assertSame('1234567890123456', $account->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $account = new Account('1234567890123456');

        $this->assertSame("1234567890123456\n", (string) $account);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('Account exceeds maximum length of 16 characters');

        // Create an account string longer than 16 characters
        new Account(str_repeat('1', 17));
    }

    #[Test]
    public function it_accepts_account_at_max_length(): void
    {
        // 16 characters
        $account = new Account(str_repeat('1', 16));

        $this->assertSame(16, strlen($account->value));
    }

    #[Test]
    public function it_accepts_short_account_numbers(): void
    {
        $account = new Account('123456');

        $this->assertSame('123456', $account->value);
    }

    #[Test]
    public function it_throws_exception_when_invalid_characters_are_provided(): void
    {
        $this->expectException(InvalidCharacterException::class);

        new Account('1234@789');
    }
}

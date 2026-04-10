<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Sender;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Sender\SenderAccount;

final class SenderAccountTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_account(): void
    {
        $account = new SenderAccount('1234567890123456');

        $this->assertSame('1234567890123456', $account->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $account = new SenderAccount('1234567890123456');

        $this->assertSame("1234567890123456\n", (string) $account);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('Sender account exceeds maximum length of 16 characters');

        // Create an account string longer than 16 characters
        new SenderAccount(str_repeat('1', 17));
    }

    #[Test]
    public function it_accepts_account_at_max_length(): void
    {
        // 16 characters
        $account = new SenderAccount(str_repeat('1', 16));

        $this->assertSame(16, strlen($account->value));
    }

    #[Test]
    public function it_creates_from_int(): void
    {
        $account = SenderAccount::fromInt(1234567890123456);

        $this->assertSame('1234567890123456', $account->value);
    }

    #[Test]
    public function it_creates_from_float(): void
    {
        $account = SenderAccount::fromFloat(1234567890123456.0);

        $this->assertSame('1234567890123456', $account->value);
    }

    #[Test]
    public function it_accepts_short_account_numbers(): void
    {
        $account = new SenderAccount('123456');

        $this->assertSame('123456', $account->value);
    }
}

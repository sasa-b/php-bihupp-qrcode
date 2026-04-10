<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Recipient;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;

final class RecipientAccountTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_account(): void
    {
        $account = new RecipientAccount('BA391234567890123456');

        $this->assertSame('BA391234567890123456', $account->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $account = new RecipientAccount('BA391234567890123456');

        $this->assertSame("BA391234567890123456\n", (string) $account);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('Recipient account exceeds maximum length of 339 characters');

        // Create an account string longer than 339 characters
        new RecipientAccount(str_repeat('1', 340));
    }

    #[Test]
    public function it_accepts_account_at_max_length(): void
    {
        // 339 characters
        $account = new RecipientAccount(str_repeat('1', 339));

        $this->assertSame(339, strlen($account->value));
    }

    #[Test]
    public function it_creates_from_int(): void
    {
        $account = RecipientAccount::fromInt(1234567890123456);

        $this->assertSame('1234567890123456', $account->value);
    }

    #[Test]
    public function it_creates_from_float(): void
    {
        $account = RecipientAccount::fromFloat(1234567890123456.0);

        $this->assertSame('1234567890123456', $account->value);
    }

    #[Test]
    public function it_accepts_iban_format(): void
    {
        $account = new RecipientAccount('BA391234567890123456');

        $this->assertSame('BA391234567890123456', $account->value);
    }

    #[Test]
    public function it_accepts_long_account_numbers(): void
    {
        $longAccount = 'BA39'.str_repeat('1', 100);
        $account = new RecipientAccount($longAccount);

        $this->assertSame($longAccount, $account->value);
    }
}

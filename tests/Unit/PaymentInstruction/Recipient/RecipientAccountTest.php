<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Recipient;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;

final class RecipientAccountTest extends TestCase
{
    #[Test]
    public function it_creates_with_valid_account_list(): void
    {
        $account = RecipientAccount::from(
            new Account('1234567890123456'),
            new Account('9876543210987654'),
        );

        $this->assertSame('1234567890123456,9876543210987654', $account->value);
    }

    #[Test]
    public function it_creates_from_single_account(): void
    {
        $account = RecipientAccount::from(new Account('1234567890123456'));

        $this->assertSame('1234567890123456', $account->value);
    }

    #[Test]
    public function it_creates_from_multiple_accounts(): void
    {
        $account = RecipientAccount::from(
            new Account('1234567890123456'),
            new Account('9876543210987654'),
        );

        $this->assertSame('1234567890123456,9876543210987654', $account->value);
    }

    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $account = RecipientAccount::from(new Account('1234567890123456'));

        $this->assertSame("1234567890123456\n", (string) $account);
    }

    #[Test]
    public function it_throws_exception_when_no_accounts_provided(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('At least one account is required.');

        RecipientAccount::from();
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_accounts(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Maximum of 20 accounts is allowed.');

        RecipientAccount::from(...array_fill(0, 21, new Account('1234567890123456')));
    }

    #[Test]
    public function it_accepts_twenty_accounts_at_max_total_length(): void
    {
        // 20 accounts × 16 chars + 19 commas = exactly 339 chars (the limit)
        $account = RecipientAccount::from(...array_fill(0, 20, new Account('1234567890123456')));

        $this->assertSame(339, strlen($account->value));
    }
}

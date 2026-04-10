<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\PaymentInstruction\Recipient;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;

final class RecipientAccountTest extends TestCase
{
    #[Test]
    public function it_converts_to_string_that_ends_with_lf_char(): void
    {
        $account = new RecipientAccount('1234567891234');

        $this->assertSame("1234567891234\n", (string) $account);
    }

    #[Test]
    public function it_throws_exception_when_exceeding_max_length(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('RecipientAccount exceeds maximum length of 339 characters');

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
    public function it_throws_exception_when_invalid_characters_are_provided(): void
    {
        $this->expectException(InvalidCharacterException::class);

        new RecipientAccount('BA39@1234567890');
    }
}

<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\Tests\Unit\QRCode\Reader;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sco\BihuppQRCode\QRCode\Reader;
use Sco\BihuppQRCode\QRCode\Reader\SuccessScanResult;
use Sco\BihuppQRCode\QRCode\ReadSource\Filepath;

final class ReaderTest extends TestCase
{
    #[Test]
    public function it_reads_a_payment_instruction_from_a_qr_code_png(): void
    {
        $result = Reader::read(new Filepath(__DIR__.'/example.png'));

        $this->assertInstanceOf(SuccessScanResult::class, $result);

        $expected = <<<QRCODE
        BIHUPP10
        Marko Marković
        Ulica Meše Selimovića 12
        78000 Banja Luka

        Račun za el. energiju
        2024001
        Pero Perić
        Titova 1
        71000 Sarajevo
        1234567890123456
        9876543210987654
        000000000010000
        BAM
        N
        
        
        
        
        
        



        QRCODE;

        $this->assertSame($expected, (string) $result->paymentInstruction);
        $this->assertSame($expected, $result->rawPayload);

        $instruction = $result->paymentInstruction;

        $this->assertNotNull($instruction->sender);
        $this->assertSame('Marko Marković', $instruction->sender->name->value);
        $this->assertSame('Ulica Meše Selimovića 12', $instruction->sender->address->addressLine1->value);
        $this->assertSame('78000 Banja Luka', $instruction->sender->address->addressLine2->value);
        $this->assertNull($instruction->sender->phoneNumber);
        $this->assertSame('1234567890123456', $instruction->sender->account->value);

        $this->assertSame('Račun za el. energiju', $instruction->purpose->value);
        $this->assertNotNull($instruction->reference);
        $this->assertSame('2024001', $instruction->reference->value);

        $this->assertSame('Pero Perić', $instruction->recipient->name->value);
        $this->assertSame('Titova 1', $instruction->recipient->address->addressLine1->value);
        $this->assertSame('71000 Sarajevo', $instruction->recipient->address->addressLine2->value);

        $this->assertSame('000000000010000', $instruction->amount->value);
        $this->assertSame('BAM', $instruction->currency->value);
        $this->assertSame('N', $instruction->paymentPriority->value);
        $this->assertNull($instruction->publicRevenue);
    }
}

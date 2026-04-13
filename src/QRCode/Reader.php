<?php

declare(strict_types=1);

namespace Sco\BihuppQRCode\QRCode;

use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine1;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Currency;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPriority;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\EmptyLine;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;
use Sco\BihuppQRCode\PaymentInstruction\Line;
use Sco\BihuppQRCode\PaymentInstruction\Lines;
use Sco\BihuppQRCode\PaymentInstruction\Name;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\BudgetOrgCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\MunicipalCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentReference as PublicRevenuePaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\RevenueType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\SenderTaxId;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\TaxPeriodDate;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\PhoneNumber;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;
use Sco\BihuppQRCode\PaymentInstruction\Version;
use Sco\BihuppQRCode\QRCode\Exception\UnknownScanException;
use Sco\BihuppQRCode\QRCode\Reader\FailureScanResult;
use Sco\BihuppQRCode\QRCode\Reader\SuccessScanResult;
use Sco\BihuppQRCode\QRCode\ReadSource\Filepath;
use Zxing\QrReader;
use Zxing\Result;

final class Reader
{
    /**
     * @param array<string,mixed> $hints
     */
    public static function read(ReadSource $source, array $hints = [
        'CHARACTER_SET' => 'UTF-8',
        'TRY_HARDER' => true,
    ]): ScanResult
    {
        if ($source instanceof Filepath) {
            $sourceType = QrReader::SOURCE_TYPE_FILE;
        } else {
            $sourceType = QrReader::SOURCE_TYPE_BLOB;
        }

        $qrcode = new QrReader(
            imgSource: $source->value,
            sourceType: $sourceType,
            useImagickIfAvailable: $source->extension === ImageExtension::Imagick
        );

        $qrcode->decode($hints);

        $result = $qrcode->getResult();

        if ($result instanceof Result) {
            try {
                return new SuccessScanResult(
                    paymentInstruction: self::parse($result->getText())->toPaymentInstruction(),
                    rawPayload: $result->toString()
                );
            } catch (\Exception $exception) {
                return new FailureScanResult(error: $exception, rawPayload: $result->toString());
            }
        }

        return new FailureScanResult(error: $qrcode->getError() ?? new UnknownScanException(), rawPayload: null);
    }

    private static function parse(string $payload): Lines
    {
        $lines = [new Version()];

        foreach (explode(Line::END, $payload) as $i => $line) {
            $lines[] = match ($i) {
                1, 7 => $line === '' ? new EmptyLine() : new Name($line),
                2, 8 => $line === '' ? new EmptyLine() : new AddressLine1($line),
                3, 9 => $line === '' ? new EmptyLine() : new AddressLine2($line),
                4 => $line === '' ? new EmptyLine() : new PhoneNumber($line),
                5 => $line === '' ? new EmptyLine() : new PaymentPurpose($line),
                6 => $line === '' ? new EmptyLine() : new PaymentReference($line),
                10 => $line === '' ? new EmptyLine() : new Account($line),
                11 => $line === '' ? new EmptyLine() : new RecipientAccount(
                    ...array_map(static fn (string $account) => new Account($account), explode(',', $line))
                ),
                12 => $line === '' ? new EmptyLine() : new Amount($line),
                13 => new Currency(),
                14 => $line === 'D' ? PaymentPriority::urgent() : PaymentPriority::regular(),
                15 => $line === '' ? new EmptyLine() : new SenderTaxId($line),
                16 => $line === '' ? new EmptyLine() : new PaymentType($line),
                17 => $line === '' ? new EmptyLine() : new RevenueType($line),
                18, 19 => $line === '' ? new EmptyLine() : TaxPeriodDate::fromDate(\DateTimeImmutable::createFromFormat('dmY', $line) ?: throw new InvalidValueException("Invalid tax period date format: $line.")),
                20 => $line === '' ? new EmptyLine() : new MunicipalCode($line),
                21 => $line === '' ? new EmptyLine() : new BudgetOrgCode($line),
                22 => $line === '' ? new EmptyLine() : new PublicRevenuePaymentReference($line),
                default => new EmptyLine(),
            };
        }

        return new Lines(...$lines);
    }
}

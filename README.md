# php-bihupp-qrcode

<p style="text-align: center">
  <img src="doc/test.svg" alt="BIHUPP QR code example" width="250"/>
</p>

PHP biblioteka koja implementira BIHUPP10 standard QR koda za instrukcije bankovnog plaćanja u Bosni i Hercegovini.

BIHUPP (Bosansko-hercegovački unutrašnji platni promet) definiše strukturirani tekstualni sadržaj koji se može enkodirati u obliku QR koda, koji banke skeniraju kako bi automatski popunile naloge za plaćanje. Ovaj format je ustanovljen od strane [Udruženja Banaka Bosne i Hercegovine](https://ubbih.ba/).

Dodavanje QR koda u definisanom formatu na računima omogućava krajnjim korisnicima plaćanje pomoću skeniranja QR koda kroz njihovo mobilno bankarstvo.

Za Kotlin biblioteku idite na [kotlin-bihupp-qrcode](https://github.com/sasa-b/kotlin-bihupp-qrcode).

---------------------------------------------------------

PHP library implementing the **BIHUPP** QR Code standard for bank payment instructions in Bosnia and Herzegovina.

BIHUPP (_Bosansko-Hercegovački Unutrašnji Platni Promet_) defines a structured text payload that can be encoded as a QR code that banks scan to pre-fill payment forms. This format was established by the [Association of Banks of Bosnia and Herzegovina](https://ubbih.ba/).

Adding a QR code in the defined format to invoices enables end-users to make payments by scanning the QR code through their mobile banking app.

For Kotlin library go to [kotlin-bihupp-qrcode](https://github.com/sasa-b/kotlin-bihupp-qrcode).

## Table of contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Quick start](#quick-start)
- [Usage](#usage)
  - [Standard bank transfer](#standard-bank-transfer)
  - [Omitting the sender](#omitting-the-sender)
  - [Multiple recipient accounts](#multiple-recipient-accounts)
  - [Including a sender phone number](#including-a-sender-phone-number)
  - [Public revenue payment](#public-revenue-payment-tax-fees-etc)
  - [Reading the payload string](#reading-the-payload-string)
- [QR code output](#qr-code-output)
  - [SVG (default)](#svg-default)
  - [PNG / JPEG — GD extension](#png--jpeg--gd-extension)
  - [PNG / JPEG — Imagick extension](#png--jpeg--imagick-extension)
  - [Base64 data-URI link](#base64-data-uri-link)
  - [Custom renderer](#custom-renderer)
- [Reading a QR code](#reading-a-qr-code)
- [Field reference](#field-reference)
  - [`PaymentInstruction` constructor](#paymentinstruction-constructor)
  - [Field constraints](#field-constraints)
  - [Allowed character set](#allowed-character-set)
- [Amount handling](#amount-handling)
- [Payment priority](#payment-priority)
- [Exception handling](#exception-handling)
- [Contribute](#contribute)
- [License](#license)

## Requirements

- PHP 8.3+
- [`chillerlan/php-qrcode`](https://github.com/chillerlan/php-qrcode) ^6.0
- [`khanamiryan/qrcode-detector-decoder`](https://github.com/khanamiryan/php-qrcode-detector-decoder) ^2.0 _(required for QR code reading)_

## Installation

```bash
composer require sasa-b/php-bihupp-qrcode
```

## Quick start

```php
use Sco\BihuppQRCode\PaymentInstruction\Address\Address;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine1;
use Sco\BihuppQRCode\PaymentInstruction\Address\AddressLine2;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Amount;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPurpose;
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\Detail\Sender;
use Sco\BihuppQRCode\PaymentInstruction\Name;
use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\Recipient;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;

$instruction = new PaymentInstruction(
    sender: new Sender(
        name: Name::individual('Marko', 'Marković'),
        address: new Address(
            addressLine1: AddressLine1::from('Ulica Meše Selimovića', '12'),
            addressLine2: AddressLine2::from('78000', 'Banja Luka'),
        ),
        account: new Account('1234567890123456'),
    ),
    recipient: new Recipient(
        name: Name::business('Vodovod d.o.o.'),
        address: new Address(
            addressLine1: AddressLine1::from('Kralja Petra I Karađorđevića', '97'),
            addressLine2: AddressLine2::from('78000', 'Banja Luka'),
        ),
        account: new RecipientAccount(new Account('9876543210987654')),
    ),
    purpose: new PaymentPurpose('Račun za vodu - april 2024'),
    reference: new PaymentReference('1234-5678-001'),
    amount: new Amount('9862'), // amount in pennies (= 98.62 BAM)
);

// Render as SVG (default)
$svg = $instruction->toQRCode();
```

## Usage

### Standard bank transfer

```php
use Sco\BihuppQRCode\PaymentInstruction\Detail\PaymentPriority;

$instruction = new PaymentInstruction(
    sender: $sender,
    recipient: $recipient,
    purpose: new PaymentPurpose('Invoice payment'),
    reference: new PaymentReference('INV-2024-001'),
    amount: new Amount('10000'),           // 100.00 BAM
    paymentPriority: PaymentPriority::regular(), // defaults to regular ('N')
);
```

### Omitting the sender

When `sender` is omitted, the bank pre-fills payer details from the logged-in user's session.

```php
use Sco\BihuppQRCode\PaymentInstruction\Detail\Sender;

$instruction = new PaymentInstruction(
    sender: null,
    recipient: $recipient,
    purpose: new PaymentPurpose('Troškovi vode'),
    reference: new PaymentReference('1445-26554-11222'),
    amount: new Amount('9862'),
);
```

### Multiple recipient accounts

Up to 20 recipient accounts can be specified.

```php
use Sco\BihuppQRCode\PaymentInstruction\Detail\Account;
use Sco\BihuppQRCode\PaymentInstruction\Recipient\RecipientAccount;

$account = new RecipientAccount(
    new Account('1234567890123456'),
    new Account('9876543210987654'),
    new Account('1111222233334444'),
);
```

### Including a sender phone number

```php
use Sco\BihuppQRCode\PaymentInstruction\Recipient\PhoneNumber;

$sender = new Sender(
    name: Name::individual('Ana', 'Anić'),
    address: new Address(
        addressLine1: AddressLine1::from('Kralja Tomislava', '5'),
        addressLine2: AddressLine2::from('88000', 'Mostar'),
    ),
    account: new Account('1234567890123456'),
    phoneNumber: new PhoneNumber('+38761234567'), // E.164 format, "+" prefix required
);
```

### Public revenue payment (tax, fees, etc.)

Public revenue payments require additional fields grouped in a `PublicRevenueInstruction` object.

```php
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenueInstruction;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\BudgetOrgCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\MunicipalCode;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentReference as PublicRevenuePaymentReference;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\PaymentType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\RevenueType;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\SenderTaxId;
use Sco\BihuppQRCode\PaymentInstruction\PublicRevenue\TaxPeriodDate;

$instruction = new PaymentInstruction(
    sender: new Sender(
        name: Name::business('Example Company d.o.o.'),
        address: new Address(
            addressLine1: AddressLine1::from('Veselina Masleše', '20'),
            addressLine2: AddressLine2::from('78000', 'Banja Luka'),
        ),
        account: new Account('1234567890123456'),
    ),
    recipient: new Recipient(
        name: new Name('Trezor'),
        address: new Address(
            addressLine1: AddressLine1::from('Aleja Svetog Save', '13'),
            addressLine2: AddressLine2::from('78000', 'Banja Luka'),
        ),
        account: new RecipientAccount(new Account('1610000010680092')),
    ),
    purpose: new PaymentPurpose('Porez na dobit'),
    reference: null,
    amount: new Amount('500000'), // 5000.00 BAM
    publicRevenue: new PublicRevenueInstruction(
        senderTaxId: new SenderTaxId('4200730150004'),  // 13-digit tax ID (JIB/JMBG)
        paymentType: new PaymentType('3'),
        revenueType: new RevenueType('712115'),
        taxPeriodStartDate: TaxPeriodDate::fromDate(new DateTimeImmutable('2024-01-01')),
        taxPeriodEndDate: TaxPeriodDate::fromDate(new DateTimeImmutable('2024-12-31')),
        municipalCode: new MunicipalCode('077'),
        budgetCode: new BudgetOrgCode('1200200'),
        paymentReference: new PublicRevenuePaymentReference('7110578163'),
    ),
);
```

### Reading the payload string

`PaymentInstruction` implements `Stringable`. Cast it to `string` to inspect or store the raw BIHUPP payload, or get an array of lines with `lines()`:

```php
echo (string) $instruction;
// BIHUPP10
// Example Company d.o.o.
// Zmaja od Bosne 75
// 71000 Sarajevo
// ...

var_dump($instruction->lines());
```

## QR code output

### SVG (default)

```php
$svg = $instruction->toQRCode(); // returns SVG XML string
```

### PNG / JPEG — GD extension

Requires the [`gd`](https://www.php.net/manual/en/book.image.php) extension. Throws `MissingImageExtension` if it is not loaded.

```php
use Sco\BihuppQRCode\QRCode\RenderStrategy\GDPng;
use Sco\BihuppQRCode\QRCode\RenderStrategy\GDJpeg;

$png  = $instruction->toQRCode(renderStrategy: new GDPng());  // PNG binary
$jpeg = $instruction->toQRCode(renderStrategy: new GDJpeg()); // JPEG binary
```

### PNG / JPEG — Imagick extension

Requires the [`imagick`](https://www.php.net/manual/en/book.imagick.php) extension. Throws `MissingImageExtension` if it is not loaded.

```php
use Sco\BihuppQRCode\QRCode\RenderStrategy\ImagickPng;
use Sco\BihuppQRCode\QRCode\RenderStrategy\ImagickJpeg;

$png  = $instruction->toQRCode(renderStrategy: new ImagickPng());  // PNG binary
$jpeg = $instruction->toQRCode(renderStrategy: new ImagickJpeg()); // JPEG binary
```

### Base64 data-URI link

`Base64Link` wraps any `ImageRenderStrategy` and returns a base64 data URI. Defaults to SVG when no strategy is passed.

```php
use Sco\BihuppQRCode\QRCode\RenderStrategy\Base64Link;
use Sco\BihuppQRCode\QRCode\RenderStrategy\GDPng;
use Sco\BihuppQRCode\QRCode\RenderStrategy\ImagickJpeg;

$svgUri  = $instruction->toQRCode(renderStrategy: new Base64Link());                  // data:image/svg+xml;base64,...
$pngUri  = $instruction->toQRCode(renderStrategy: new Base64Link(new GDPng()));       // data:image/png;base64,...
$jpegUri = $instruction->toQRCode(renderStrategy: new Base64Link(new ImagickJpeg())); // data:image/jpeg;base64,...
```

### Custom renderer

Implement `Renderer` and `RenderStrategy` to integrate any QR library or add custom options (logo overlay, colours, size, etc.):

```php
use Sco\BihuppQRCode\PaymentInstruction\PaymentInstruction;
use Sco\BihuppQRCode\QRCode\Renderer;
use Sco\BihuppQRCode\QRCode\RenderStrategy;
use Sco\BihuppQRCode\QRCode\RenderStrategy\Svg;

final readonly class MyRenderer implements Renderer
{
    public function render(PaymentInstruction $data, RenderStrategy $strategy): string
    {
        // use any QR library here
        return myQrLib()->encode((string) $data);
    }
}

$svg = $instruction->toQRCode(renderer: new MyRenderer(), renderStrategy: new Svg());
```

## Reading a QR code

Use `Reader::read()` to scan a BIHUPP QR code image and reconstruct a `PaymentInstruction` from it. Pass either a file path or a binary blob as the source.

```php
use Sco\BihuppQRCode\QRCode\Reader;
use Sco\BihuppQRCode\QRCode\ReadSource\Filepath;
use Sco\BihuppQRCode\QRCode\ReadSource\Blob;
use Sco\BihuppQRCode\QRCode\Reader\SuccessScanResult;
use Sco\BihuppQRCode\QRCode\Reader\FailureScanResult;

// From a file path
$result = Reader::read(new Filepath('/path/to/qrcode.png'));

// From binary image data (e.g. an uploaded file)
$result = Reader::read(new Blob(file_get_contents('/path/to/qrcode.png')));

if ($result instanceof SuccessScanResult) {
    $instruction = $result->paymentInstruction; // PaymentInstruction
    $raw         = $result->rawPayload;         // raw BIHUPP payload string

    echo $instruction->sender->name->value;     // e.g. "Marko Marković"
    echo $instruction->amount->value;           // e.g. "000000000010000"
} else {
    // $result is FailureScanResult
    echo $result->error->getMessage();          // reason for failure
    // $result->rawPayload is null when the image contained no QR code
}
```

By default GD is used to decode the image. Pass `ImageExtension::Imagick` as the second constructor argument to use Imagick instead:

```php
use Sco\BihuppQRCode\QRCode\ImageExtension;

$result = Reader::read(new Filepath('/path/to/qrcode.png', ImageExtension::Imagick));
```

## Field reference

### `PaymentInstruction` constructor

| Parameter | Type | Required | Default                     | Notes                               |
|---|---|----------|-----------------------------|-------------------------------------|
| `sender` | `Sender` | No       | —                           | Pass `null` to let the bank auto-fill |
| `recipient` | `Recipient` | Yes      | —                           |                                     |
| `purpose` | `PaymentPurpose` | Yes      | —                           | Max 110 chars                       |
| `reference` | `PaymentReference\|null` | No       | —                           | Pass `null` to omit                 |
| `amount` | `Amount` | Yes      | —                           | Integer in pennies (pfeninga)       |
| `currency` | `Currency` | Yes       | `BAM`                       | Hardcoded to BAM by standard        |
| `paymentPriority` | `PaymentPriority` | No        | `N` (regular)               |                                     |
| `publicRevenue` | `PublicRevenue\|null` | No       | `null`                      | Required only for tax/fee payments  |
| `version` | `Version` | Yes      | `BIHUPP10`                  |                                     |

### Field constraints

#### Sender / Recipient

| Field | Class | Max length | Format |
|---|---|---|---|
| Name | `Name` | 50 | Alphanumeric + allowed chars |
| Address line 1 | `AddressLine1` | 50 | Street + number |
| Address line 2 | `AddressLine2` | 25 | Postcode + city |
| Phone | `PhoneNumber` | 15 | E.164 (`+` prefix required) |
| Sender account | `Account` | 16 | |
| Recipient account(s) | `RecipientAccount` | 339 | 1–20 accounts, comma-separated |

#### Payment detail

| Field | Class | Max length | Format                                  |
|---|---|---|-----------------------------------------|
| Purpose | `PaymentPurpose` | 110 |                                         |
| Reference | `Detail\PaymentReference` | 30 |                                         |
| Amount | `Amount` | 15 | Integer pennies (pfeninzi), zero-padded |

#### Public revenue fields

| Field | Class | Length | Format |
|---|---|---|---|
| Sender tax ID | `SenderTaxId` | 13 | Exactly 13 digits (JIB/JMBG) |
| Payment type | `PaymentType` | 1 | Single digit (`0`–`9`) |
| Revenue type | `RevenueType` | 6 | Exactly 6 digits |
| Tax period date | `TaxPeriodDate` | 8 | `DDMMYYYY` |
| Municipal code | `MunicipalCode` | 3 | Exactly 3 digits |
| Budget org code | `BudgetOrgCode` | 7 | Exactly 7 digits |
| Payment reference | `PublicRevenue\PaymentReference` | 10 | Exactly 10 digits |

### Allowed character set

All text fields accept: alphanumeric characters, Serbian/Croatian/Bosnian diacritics (`č ć đ š ž` and their uppercase forms), and the symbols `, : . ? ( ) + ' / -` and space.

Fields with stricter formats (tax IDs, codes, phone numbers) enforce their own format in addition to the above.

## Amount handling

`Amount` stores the value as an integer number of **pennies (pfeniga)** and zero-pads it to 15 digits in the payload. Use the factory helpers for other input types:

```php
Amount::fromInt(9862);         // 98.62 BAM
Amount::fromFloat(98.62);      // 98.62 BAM (strips the decimal separator)
new Amount('9862');            // same, from a string
```

## Payment priority

```php
PaymentPriority::regular();    // 'N' — standard processing
PaymentPriority::urgent();     // 'D' — urgent processing
PaymentPriority::from(Priority::Urgent);
```

## Exception handling

All validation runs at construction time. Exception types extend `BihuppQRCodeException`.:

| Exception | Thrown when |
|---|---|
| `InvalidLengthException` | Value exceeds the field's maximum character length |
| `InvalidCharacterException` | Value contains characters outside the allowed set |
| `InvalidValueException` | Value violates a field-specific format rule (digits-only, `+` prefix, etc.) |
| `MissingImageExtension` | A GD or Imagick render strategy is used but the required PHP extension is not loaded |
| `UnknownScanException` | `Reader::read()` could not decode the image and the underlying library returned no specific error |

Catch a specific type, or use `BihuppQRCodeException` to handle any library exception in one clause:

```php
use Sco\BihuppQRCode\BihuppQRCodeException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidLengthException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidCharacterException;
use Sco\BihuppQRCode\PaymentInstruction\Exception\InvalidValueException;

try {
    $name = new Name(str_repeat('A', 51)); // exceeds 50-char limit
} catch (InvalidLengthException $e) {
    // handle length violation specifically
} catch (BihuppQRCodeException $e) {
    // handle any other library exception
}
```

## Contribute

Run code quality check before raising PRs. 

```bash
composer c:q # runs php-cs-fixer and PHPStan
```

## License

MIT — see [LICENSE](LICENSE).

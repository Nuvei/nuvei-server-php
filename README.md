# SafeCharge REST API SDK for PHP

SafeCharge’s REST API SDK for PHP provides developer tools for accessing Safecharge's REST API. SafeCharge’s REST API is a simple, easy to use, secure and stateless API, which enables online merchants and service providers to process consumer payments through SafeCharge’s payment gateway. The API supports merchants of all levels of PCI certification, from their online and mobile merchant applications, and is compatible with a large variety of payment options, i.e. payment cards, alternative payment methods, etc. For SafeCharge REST API documentation, please see: https://www.safecharge.com/docs/api/

## Requirements

PHP 5.4 or later.

# Installation
### Installation via Composer
```bash
composer require safecharge-international/safecharge-php
```
### Manual
If you do not wish to use Composer, you can download the [latest release](https://github.com/SafeChargeInternational/safecharge-php/releases). Then include the `init.php` file.

```php
require_once('/path/to/safecharge-sdk/init.php');
```
## Dependencies

The PHP SDK require the following extension in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php)
- [`json`](https://secure.php.net/manual/en/book.json.php)

## Configuration
### Client
```php
$client = new \SafeCharge\Api\RestClient([
    'environment'       => \SafeCharge\Api\Environment::TEST,
    'merchantId'        => '<your merchantId>',
    'merchantSiteId'    => '<your merchantSiteId>',
    'merchantSecretKey' => '<your merchantSecretKey>',
]);
```
If your hash algorithm is md5 you should add parameter 'hashAlgorithm' with value 'md5' in the above array.

Or

```php
$client = new \SafeCharge\Api\RestClient();
$config = $client->getConfig();
$config->setEnvironment(\SafeCharge\Api\Environment::TEST);
$config->setMerchantId('<your merchantId>');
$config->setMerchantSiteId('<your merchantSiteId>');
$config->setMerchantSecretKey('<your merchantSecretKey>');
```

If your hash algorithm is md5 add the following code right after the one above:


```php
$config->setHashAlgorithm('md5');
```

### Logger

Logger can be configured with a [PSR-3 compatible logger](http://www.php-fig.org/psr/psr-3/) .

#### Example with Monolog
```php
$logger = new Monolog\Logger('safecharge-php-sdk');
$logger->pushHandler(new Monolog\Handler\StreamHandler('path/to/log', Monolog\Logger::DEBUG));
$client->setLogger($logger);
```

## Example
Safecharge's PHP SDK appends merchantId, merchantSiteId, timestamp and checksum in the request.
```php
<?php

use SafeCharge\Api\RestClient;
use SafeCharge\Tests\SimpleData;
use SafeCharge\Tests\TestCaseHelper;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../init.php';
require __DIR__ . '/../tests/TestCaseHelper.php';
require __DIR__ . '/../tests/SimpleData.php';

$config = [
    'environment'       => \SafeCharge\Api\Environment::TEST,
    'merchantId'        => '<your merchantId>',
    'merchantSiteId'    => '<your merchantSiteId>',
    'merchantSecretKey' => '<your merchantSecretKey>',
    'hashAlgorithm'     => '<sha256 or md5>'
];

$safecharge = new \SafeCharge\Api\SafeCharge();
$safecharge->initialize($config);
$paymentResponse = $safecharge->getPaymentService()->initPayment([
    'currency'       => 'EUR',
    'amount'         => '10',
    'userTokenId'    => '<user token id>',
    'paymentOption'  => [
        'card' => [
            'cardNumber'      => '<card number>',
            'cardHolderName'  => 'card name',
            'expirationMonth' => '<expiration month>',
            'expirationYear'  => '<expiration year>',
            'CVV'             => '<cvv>',
        ]
    ],
    'billingAddress' => [
        "firstName" => "<first name>",
        "lastName"  => "<last name>",
        "address"   => "<address>",
        "phone"     => "<phone number>",
        "zip"       => "<zip code>",
        "city"      => "<city>",
        'country'   => "<country ISO 3166-1 alpha-2>",
        "state"     => "<state>",
        "email"     => "<email address>",
        "county"    => "<county>",
    ]
]);

print_r($paymentResponse);

$openOrderRequest = $safecharge->getPaymentService()->openOrder([
    'userTokenId'       => '<user token id>',
    'clientUniqueId'    => '',
    'clientRequestId'   => '',
    'currency'          => SimpleData::getCurrency(),
    'amount'            => SimpleData::getAmount(),
    'amountDetails'     => SimpleData::getAmountDetails(),
    'items'             => SimpleData::getItems(),
    'deviceDetails'     => SimpleData::getDeviceDetails(),
    'userDetails'       => SimpleData::getUserDetails(),
    'shippingAddress'   => SimpleData::getShippingAddress(),
    'billingAddress'    => SimpleData::getBillingAddress(),
    'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
    'merchantDetails'   => SimpleData::getMerchantDetails(),
    'addendums'         => SimpleData::getAddEndUms(),
]);

print_r($openOrderRequest);
```

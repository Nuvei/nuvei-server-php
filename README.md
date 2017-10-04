# SafeCharge REST API SDK for PHP.

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
###Client
```php
$client = new \SafeCharge\Api\RestClient([
    'environment'       => \SafeCharge\Api\Environment::TEST,
    'merchantId'        => '<your merchantId>',
    'merchantSiteId'    => '<your merchantSiteId>',
    'merchantSecretKey' => '<your merchantSecretKey>',
]);
```

Or

```php
$client = new \SafeCharge\Api\RestClient();
$config = $client->getConfig();
$config->setEnvironment(\SafeCharge\Api\Environment::TEST);
$config->setMerchantId('<your merchantId>');
$config->setMerchantSiteId('<your merchantSiteId>');
$config->setMerchantSecretKey('<your merchantSecretKey>');
```
### Logger

Logger can be configured with a [`PSR-3` compatible logger][psr3] so that messages end up there instead of `error_log`:

####Example with Monolog
```php
$logger = new Monolog\Logger('safecharge-php-sdk');
$logger->pushHandler(new Monolog\Handler\StreamHandler('path/to/log', Monolog\Logger::DEBUG));
$client->setLogger($logger);
```

##Example
Safecharge's PHP SDK appends merchantId, merchantSiteId, timestamp and checksum in the request.
```php
<?php
$client = new \SafeCharge\Api\RestClient([
    'environment'       => \SafeCharge\Api\Environment::TEST,
    'merchantId'        => '<your merchantId>',
    'merchantSiteId'    => '<your merchantSiteId>',
    'merchantSecretKey' => '<your merchantSecretKey>',
]);

$authenticationService = new \SafeCharge\Api\Service\AuthenticationManagement($client);

$authenticationResponse = $authenticationService->getSessionToken([
    'clientRequestId' => '1'
]);

$openOrderParams = [
    'sessionToken'      => $authenticationResponse['sessionToken'],
    'currency'          => 'USD',
    'amount'            => "10",
    'amountDetails'     => [
        "totalShipping" => "0",
        "totalHandling" => "0",
        "totalDiscount" => "0",
        "totalTax"      => "0"
    ],
    'items'             => [
        [
            "id"       => "1",
            "name"     => "name",
            "price"    => "10",
            "quantity" => "1"
        ]
    ],
];

$orderService = new \SafeCharge\Api\Service\OrdersManagement($client);

$openOrderResponse = $orderService->openOrder($openOrderParams);

```


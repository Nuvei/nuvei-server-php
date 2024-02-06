<?php

use PHPUnit\Framework\TestCase;
use Nuvei\Api\RestClient;
use Nuvei\Tests\SimpleData;
use Nuvei\Tests\TestCaseHelper;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../init.php';
require __DIR__ . '/../tests/TestCaseHelper.php';
require __DIR__ . '/../tests/SimpleData.php';

$config = [
    'environment'       => 'test',
    'sslVerifyPeer'     => true,
    'merchantId'        => '5078248497400694938',
    'merchantSiteId'    => '142163',
    'merchantSecretKey' => 'F0EpuOTjZPIKw5SGcNGyISClL1zaVnArABS65EkfUIwVmzgNbEiiQeesGp4N79Rg',
    'hashAlgorithm'     => 'sha256'
];

/**
 * Option 1
 */
//$nuvei = new \Nuvei\Api\Nuvei($config); //Option 1
/**
 * Option 2
 */
$nuvei = new \Nuvei\Api\Nuvei();
$nuvei->initialize($config);
$paymentResponse = $nuvei->getPaymentService()->initPayment([
    'currency'       => 'EUR',
    'amount'         => '10',
    'userTokenId'    => TestCaseHelper::getUserTokenId(),
    'paymentOption'  => [
        'card' => [
            'cardNumber'      => '4000027891380961',
            'cardHolderName'  => 'John Smith',
            'expirationMonth' => '12',
            'expirationYear'  => '2030',
            'CVV'             => '217',
        ]
    ],
    'billingAddress' => [
        "firstName" => "some first name",
        "lastName"  => "some last name",
        "address"   => "some street",
        "phone"     => "972502457558",
        "zip"       => "123456",
        "city"      => "some city",
        'country'   => "DE",
        "state"     => "",
        "email"     => "someemail@somedomain.com",
        "county"    => "Anchorage",
    ]
]);
print_r($paymentResponse);

//$openOrderRequest = $nuvei->getPaymentService()->openOrder([
//    'userTokenId'       => TestCaseHelper::getUserTokenId(),
//    'clientUniqueId'    => '',
//    'clientRequestId'   => '',
//    'currency'          => SimpleData::getCurrency(),
//    'amount'            => SimpleData::getAmount(),
//    'amountDetails'     => SimpleData::getAmountDetails(),
//    'items'             => SimpleData::getItems(),
//    'deviceDetails'     => SimpleData::getDeviceDetails(),
//    'userDetails'       => SimpleData::getUserDetails(),
//    'shippingAddress'   => SimpleData::getShippingAddress(),
//    'billingAddress'    => SimpleData::getBillingAddress(),
//    'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
//    'merchantDetails'   => SimpleData::getMerchantDetails(),
//    'addendums'         => SimpleData::getAddEndUms(),
//]);
//
//
//print_r($openOrderRequest);

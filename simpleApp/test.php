<?php

use SafeCharge\Api\RestClient;
use SafeCharge\Tests\SimpleData;
use SafeCharge\Tests\TestCaseHelper;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../init.php';
require __DIR__ . '/../tests/TestCaseHelper.php';
require __DIR__ . '/../tests/SimpleData.php';

$config = [
    'environment'       => 'test',
    'merchantId'        => '5078248497400694938',
    'merchantSiteId'    => '142163',
    'merchantSecretKey' => 'F0EpuOTjZPIKw5SGcNGyISClL1zaVnArABS65EkfUIwVmzgNbEiiQeesGp4N79Rg',
    'hashAlgorithm'     => 'sha256'
];

/**
 * Option 1
 */
//$safecharge = new \SafeCharge\Api\SafeCharge($config); //Option 1
/**
 * Option 2
 */
$safecharge = new \SafeCharge\Api\SafeCharge();
$safecharge->initialize($config);
$paymentResponse = $safecharge->getPaymentService()->initPayment([
    'currency'       => 'EUR',
    'amount'         => '10',
    'userTokenId'    => 'emilg@safecharge.com',
    'paymentOption'  => [
        'card' => [
            'cardNumber'      => '4012001037141112',
            'cardHolderName'  => 'some name',
            'expirationMonth' => '01',
            'expirationYear'  => '2020',
            'CVV'             => '122',
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

//$openOrderRequest = $safecharge->getPaymentService()->openOrder([
//    'userTokenId'       => 'emilg@safecharge.com',
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

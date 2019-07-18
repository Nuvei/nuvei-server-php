<?php

use SafeCharge\Api\RestClient;

require __DIR__ . '/../init.php';
$safecharge = new \SafeCharge\Api\SafeCharge();
$safecharge->initialize([
    'environment'       => 'test',
    'merchantId'        => '5078248497400694938',
    'merchantSiteId'    => '142163',
    'merchantSecretKey' => 'F0EpuOTjZPIKw5SGcNGyISClL1zaVnArABS65EkfUIwVmzgNbEiiQeesGp4N79Rg',
    'hashAlgorithm'     => 'sha256'
]);
try {
    $paymentResponse = $safecharge->getPaymentService()->createPayment([
        'sessionToken'   => $safecharge->getSessionToken(),
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
} catch (\SafeCharge\Api\Exception\ConfigurationException $e) {
} catch (\SafeCharge\Api\Exception\ConnectionException $e) {
} catch (\SafeCharge\Api\Exception\ResponseException $e) {
} catch (\SafeCharge\Api\Exception\ValidationException $e) {
}



<?php


namespace SafeCharge\Tests;


class SimpleData
{

    public static function getCurrency()
    {
        return 'EUR';
    }

    public static function getAmount()
    {
        return "10";
    }

    public static function getCurrencyConversion()
    {
        return [
            'originalAmount' => "10",
            'originalCurrency' => "USD"
        ];
    }

    public static function getAmountDetails()
    {
        return [
            "totalShipping" => "0",
            "totalHandling" => "0",
            "totalDiscount" => "0",
            "totalTax"      => "0"
        ];
    }

    public static function getItems()
    {
        return [
            [
                "id"       => "1",
                "name"     => "name",
                "price"    => self::getAmount(),
                "quantity" => "1"
            ]
        ];
    }

    public static function getDeviceDetails()
    {
        return [
            "deviceType" => "MOBILE",
            "deviceName" => "deviceName",
            "deviceOS"   => "deviceOS",
            "browser"    => "browser",
            "ipAddress"  => "192.168.1.54"
        ];
    }

    public static function getUserDetails()
    {
        return [
            "firstName" => "some first name",
            "lastName"  => "some last name",
            "phone"     => "972502457558",
            "email"     => "someemail@somedomain.com",
            "address"   => "some street",
            "city"      => "some city",
            "zip"       => "123456",
            "country"   => "US",
            "state"     => "AK",
            "county"    => "Anchorage"
        ];
    }

    public static function getShippingAddress()
    {
        return [
            "firstName" => "some first name",
            "lastName"  => "some last name",
            "cell"      => "",
            "phone"     => "972502457558",
            "email"     => "someemail@somedomain.com",
            "address"   => "some street",
            "city"      => "some city",
            "zip"       => "123456",
            "country"   => "US",
            "state"     => "AK",
            "county"    => "Anchorage"
        ];
    }

    public static function getBillingAddress($addCountryCode = false)
    {
        $countryParameter = $addCountryCode ? 'countryCode' : 'country';
        return [
            "firstName"       => "some first name",
            "lastName"        => "some last name",
            "address"         => "some street",
            "phone"           => "972502457558",
            "zip"             => "123456",
            "city"            => "some city",
            $countryParameter => "DE",
            "state"           => "",
            "email"           => "someemail@somedomain.com",
            "county"          => "Anchorage",
        ];
    }

    public static function getMerchantDetails()
    {
        return [
            "customInfoField1"  => "",
            "customInfoField2"  => "",
            "customInfoField3"  => "",
            "customInfoField4"  => "",
            "customInfoField5"  => "",
            "customInfoField6"  => "",
            "customInfoField7"  => "",
            "customInfoField8"  => "",
            "customInfoField9"  => "",
            "customInfoField10" => "",
            "customInfoField11" => "",
            "customInfoField12" => "",
            "customInfoField13" => "",
            "customInfoField14" => "",
            "customInfoField15" => "",
        ];
    }

    public static function getDynamicDescriptor()
    {
        return [
            "merchantName"  => "merchantName",
            "merchantPhone" => "+4412378"
        ];
    }

    public static function getCardNumber()
    {
        return '4012001037141112';
    }

    public static function getCardData($cardNumber = false, $ccTempToken = false)
    {
        if ($cardNumber === false) {
            $cardNumber = '4012001037141112';
        }
        if ($ccTempToken == false) {
            return [
                'cardNumber'      => $cardNumber,
                'cardHolderName'  => 'some name',
                'expirationMonth' => '01',
                'expirationYear'  => '2022',
                'CVV'             => '122',
            ];
        }

        return [
            'ccTempToken' => $ccTempToken,
            'CVV'         => '122'
        ];
    }

    public static function getCardDataVerify3d($cardNumber = false, $ccTempToken = false)
    {
        if ($cardNumber === false) {
            $cardNumber = '4000027891380961';
        }
        if ($ccTempToken == false) {
            return [
                'cardNumber'      => $cardNumber,
                'cardHolderName'  => 'CL-BRW1',
                'expirationMonth' => '01',
                'expirationYear'  => '2022',
                'CVV'             => '122',
            ];
        }

        return [
            'ccTempToken' => $ccTempToken,
            'CVV'         => '122'
        ];
    }

    public static function getCardThreeD()
    {
        return [
            'methodCompletionInd'=> 'Y',
            'version'=> '2.1.0',
            'notificationURL'=> 'wwww.Test-Notification-URL-After-The-Challange-Is-Complete-Which-Recieves-The-CRes-Message.com',
            'merchantURL'=> 'www.The-Merchant-Website-Fully-Quallified-URL.com',
            'platformType'=> '02',
            'v2AdditionalParams'=> [
                'challengePreference'=> '02',
                'deliveryEmail'=> 'The_Email_Address_The_Merchandise_Was_Delivered@yoyoyo.com',
                'deliveryTimeFrame'=> '03',
                'giftCardAmount'=> '1',
                'giftCardCount'=> '41',
                'giftCardCurrency'=> 'USD',
                'preOrderDate'=> '20220511',
                'preOrderPurchaseInd'=> '02',
                'reorderItemsInd'=> '01',
                'shipIndicator'=> '06',
                'rebillExpiry'=> '20200101',
                'rebillFrequency'=> '13',
                'challengeWindowSize'=> '05'
            ],
            'browserDetails'=> [
                'acceptHeader'=> 'Y',
                'ip'=> '190.0.23.160',
                'javaEnabled'=> 'true',
                'javaScriptEnabled'=> 'true',
                'language'=> 'BG',
                'colorDepth'=> '48',
                'screenHeight'=> '1024',
                'screenWidth'=> '1024',
                'timeZone'=> '+3',
                'userAgent'=> 'Mozilla'
            ],
            'account'=> [
                'age'=> '05',
                'lastChangeDate'=> '20190220',
                'lastChangeInd'=> '04',
                'registrationDate'=> '20190221',
                'passwordChangeDate'=> '20190222',
                'resetInd'=> '01',
                'purchasesCount6M'=> '6',
                'addCardAttempts24H'=> '24',
                'transactionsCount24H'=> '23',
                'transactionsCount1Y'=> '998',
                'cardSavedDate'=> '20190223',
                'cardSavedInd'=> '02',
                'addressFirstUseDate'=> '20190224',
                'addressFirstUseInd'=> '03',
                'nameInd'=> '02',
                'suspiciousActivityInd'=> '01'
            ],
            'acquirer'=> [
                'bin'=> '665544',
                'merchantId'=> '9876556789',
                'merchantName'=> 'Acquirer Merchant Name'
            ]
        ];
    }

    public static function getUserPaymentOption()
    {
        return
            [
                'userPaymentOptionId' => '7065406',
                'CVV'                 => '234'
            ];
    }

    public static function getUrlDetails($onlyNotificationUrl = true)
    {
        if ($onlyNotificationUrl) {
            return [
                'notificationUrl' => 'https://www.safecharge.com',
            ];
        }
        return [
            'successUrl'      => 'https://www.safecharge.com',
            'failureUrl'      => 'https://www.safecharge.com',
            'pendingUrl'      => 'https://www.safecharge.com',
            'notificationUrl' => 'https://www.safecharge.com',
        ];
    }

    public static function getUserAccountDetails()
    {
        return [
            'email' => 'user@mail.com'
        ];
    }

    public static function getAddEndUms()
    {
        return [
            'localPayment' => [
                'nationalId'            => '012345678',
                'debitType'             => 'RegularCredit',
                'firstInstallment'      => '1',
                'periodicalInstallment' => '1',
                'numberOfInstallments'  => '2'
            ]
        ];
    }

}
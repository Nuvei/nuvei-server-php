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

    public static function getCarData($cardNumber = false, $ccTempToken = false)
    {
        if ($cardNumber === false) {
            $cardNumber = '4012001037141112';
        }
        if ($ccTempToken == false) {
            return [
                'cardNumber'      => $cardNumber,
                'cardHolderName'  => 'some name',
                'expirationMonth' => '01',
                'expirationYear'  => '2020',
                'CVV'             => '122',
            ];
        }

        return [
            'ccTempToken' => $ccTempToken,
            'CVV'         => '122'
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
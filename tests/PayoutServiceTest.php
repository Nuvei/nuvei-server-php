<?php
/**
 * MIT License
 *
 * Copyright (c) 2017 Safecharge Bulgaria ltd
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace SafeCharge\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use SafeCharge\Api\Exception\ConfigurationException;
use SafeCharge\Api\Exception\ConnectionException;
use SafeCharge\Api\Exception\ResponseException;
use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Service\Payments\Payout;

class PayoutTest extends TestCase
{
    private $service;

    /**
     * PayoutTest constructor.
     * @throws ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();

        $this->service = new Payout(TestCaseHelper::getClient());
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function testPayout()
    {
        $params = [
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientRequestId'   => '100',
            'clientUniqueId'    => '12345',
            'amount'            => "9.0",
            'currency'          => SimpleData::getCurrency(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'userPaymentOption' => [
                'userPaymentOptionId' => TestCaseHelper::getUPOCreditCardId(),
                'CVV'                 => '234'
            ],
            'comment'           => 'some comment',
            'urlDetails'        => SimpleData::getUrlDetails(true),
        ];

        $response = $this->service->payout($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

}

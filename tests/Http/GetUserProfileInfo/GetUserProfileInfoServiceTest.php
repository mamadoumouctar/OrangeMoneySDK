<?php

namespace Tm\OrangeMoneySDK\Test\Http\GetUserProfileInfo;

use PHPUnit\Framework\TestCase;
use Tm\OrangeMoneySDK\Domain\Entity\OmResponseError;
use Tm\OrangeMoneySDK\Http\GetUserProfileInfo\GetUserProfileInfoService;
use Tm\OrangeMoneySDK\Http\GetUserProfileInfo\UserProfileInfo;
use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpClientResponse;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;
use Tm\OrangeMoneySDK\Http\Token\Token;

class GetUserProfileInfoServiceTest extends TestCase
{
    public function testValidPass()
    {
        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())->method('get')
            ->with('https://api.sandbox.orange-sonatel.com/api/eWallet/v1/account',
                ['Accept' => 'application/json', 'Authorization' => 'Bearer access'],
                ['msisdn' => '770000000'])
            ->willReturn(new HttpClientResponse(true, []));

        $token = new Token('access', 'refresh', false);
        $service = new GetUserProfileInfoService($client, $token);
        $service->execute($callback, '770000000');
    }

    public function testSuccessCallbackIfValidResponse()
    {
        $userProfile = UserProfileInfo::fromArray([
            "userId" => "PT000000.0000.000000",
            "firstName" => "clientF",
            "lastName" => "clientL",
            "msisdn" => "770000000",
            "balance" => [
                "value" => 20,
                "unit" => "XOF"
            ],
            "frozenBalance" => [
                "value" => 1.0,
                "unit" => "XOF"
            ],
            "grade" => "Light",
            "suspended" => "No",
            "barred" => "Sender",
            "type" => "customer"
        ]);

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->once())->method('successHandler')->with($userProfile);
        $callback->expects($this->never())->method('errorHandler');

        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())->method('get')
            ->with('https://api.sandbox.orange-sonatel.com/api/eWallet/v1/account', ['Accept' => 'application/json', 'Authorization' => 'Bearer access'], [
                'msisdn' => '770000000'
            ])->willReturn(new HttpClientResponse(true, [
                "userId" => "PT000000.0000.000000",
                "firstName" => "clientF",
                "lastName" => "clientL",
                "msisdn" => "770000000",
                "balance" => [
                    "value" => 20,
                    "unit" => "XOF"
                ],
                "frozenBalance" => [
                    "value" => 1.0,
                    "unit" => "XOF"
                ],
                "grade" => "Light",
                "suspended" => "No",
                "barred" => "Sender",
                "type" => "customer"
            ]));

        $token = new Token('access', 'refresh', false);
        $service = new GetUserProfileInfoService($client, $token);
        $service->execute($callback, '770000000');
    }

    public function testSuccessCallbackIfInValidResponse()
    {
        $data = [
            'type' => '/bad-request',
            'title' => 'Bad Request',
            'instance' => '/api/eWallet/v1/payments/onestep',
            'status' => '400',
            'code' => '24',
            'detail' => 'failed'
        ];

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->never())->method('successHandler');
        $callback->expects($this->once())->method('errorHandler')->with(OmResponseError::makeFromArray($data));

        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())->method('get')
            ->with('https://api.sandbox.orange-sonatel.com/api/eWallet/v1/account', ['Accept' => 'application/json', 'Authorization' => 'Bearer access'], [
                'msisdn' => '770000000'
            ])->willReturn(new HttpClientResponse(false, $data));

        $token = new Token('access', 'refresh', false);
        $service = new GetUserProfileInfoService($client, $token);
        $service->execute($callback, '770000000');
    }


    public function testBehaviorWithThrow()
    {
        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->never())->method('successHandler');
        $callback->expects($this->once())->method('errorHandler')->with('error');

        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())->method('get')
            ->willThrowException(new \Exception('error'));

        $token = new Token('access', 'refresh', false);
        $service = new GetUserProfileInfoService($client, $token);
        $service->execute($callback, '770000000');
    }
}

<?php

namespace Tm\OrangeMoneySDK\Test\Http\Token;

use PHPUnit\Framework\TestCase;
use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpClientResponse;
use Tm\OrangeMoneySDK\Http\io\HttpException;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;
use Tm\OrangeMoneySDK\Http\Token\Token;
use Tm\OrangeMoneySDK\Http\Token\TokenCredential;
use Tm\OrangeMoneySDK\Http\Token\TokenService;

class TokenServiceTest extends TestCase
{
    public function testBodyRequest()
    {
        $service = new TokenService($this->getClient(), new TokenCredential('id', 'secret'));
        $reflexion = new \ReflectionMethod($service, 'getRequestBody');
        $body = $reflexion->invoke($service);
        $this->assertEquals(["grant_type" => "client_credentials", "client_id" => "id", "client_secret" => "secret"], $body);
    }

    public function testRightPassToClient()
    {
        $client = $this->getClient();
        $client->expects($this->once())->method('post')
            ->with(
                'https://api.sandbox.orange-sonatel.com/oauth/token',
                ['Content-Type' => 'application/x-www-form-urlencoded'],
                [],
                [
                    'grant_type' => 'client_credentials',
                    'client_id' => 'id',
                    'client_secret' => 'secret'
                ]
            )
            ->willReturn(new HttpClientResponse(true, ['access_token' => 'access', 'refresh_token' => 'refresh']));

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();

        $tokenCredential = new TokenCredential('id', 'secret', false);
        $service = new TokenService($client, $tokenCredential);
        $service->execute($callback);
    }

    public function testFetchTokenWithValidCredentials()
    {
        $client = $this->getClient();
        $client->expects($this->once())->method('post')
            ->willReturn(new HttpClientResponse(true, ['access_token' => 'access', 'refresh_token' => 'refresh']));

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->once())->method('successHandler')->with(new Token('access', 'refresh', false));
        $callback->expects($this->never())->method('errorHandler');

        $tokenCredential = new TokenCredential('id', 'secret', false);
        $service = new TokenService($client, $tokenCredential);
        $service->execute($callback);
    }

    public function testFetchTokenWithInvalidHttpResponse()
    {
        $client = $this->getClient();
        $client->expects($this->once())->method('post')
            ->willReturn(new HttpClientResponse(false, 'error'));

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->never())->method('successHandler');
        $callback->expects($this->once())->method('errorHandler')->with('error');

        $tokenCredential = new TokenCredential('id', 'secret', false);
        $service = new TokenService($client, $tokenCredential);
        $service->execute($callback);
    }

    public function testFetchTokenWithInvalidCredentials()
    {
        $client = $this->getClient();
        $client->expects($this->once())->method('post')
            ->willThrowException(new HttpException('Orange Money Error'));

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->once())->method('errorHandler');
        $callback->expects($this->never())->method('successHandler');

        $tokenCredential = new TokenCredential('id', 'secret', false);
        $service = new TokenService($client, $tokenCredential);
        $service->execute($callback);
    }

    public function testCallbackThrowException()
    {
        $client = $this->getClient();
        $client->expects($this->once())->method('post')
            ->willReturn(new HttpClientResponse(true, ['access_token' => 'access', 'refresh_token' => 'refresh']));

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->once())->method('successHandler')->willThrowException(new \Exception('error'));
        $callback->expects($this->once())->method('errorHandler');

        $tokenCredential = new TokenCredential('id', 'secret', false);
        $service = new TokenService($client, $tokenCredential);
        $service->execute($callback);
    }

    private function getClient()
    {
        return $this->getMockBuilder(HttpClientInterface::class)->getMock();
    }
}

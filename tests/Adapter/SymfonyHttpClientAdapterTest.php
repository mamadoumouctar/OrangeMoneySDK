<?php

namespace Tm\OrangeMoneySDK\Test\Adapter;

use PHPUnit\Framework\TestCase;
use Tm\OrangeMoneySDK\Adapter\SymfonyHttpClientAdapter;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;
use Tm\OrangeMoneySDK\Http\Token\Token;
use Tm\OrangeMoneySDK\Http\Token\TokenCredential;
use Tm\OrangeMoneySDK\Http\Token\TokenService;

class SymfonyHttpClientAdapterTest extends TestCase
{
    private ?Token $token = null;

    public function testFetchTokenWithValidCredentials()
    {
        $tokenCredential = new TokenCredential(getenv('CLIENT_ID'), getenv('CLIENT_SECRET'), false);
        $service = new TokenService(new SymfonyHttpClientAdapter(), $tokenCredential);
        $service->execute(new CallbackAdapter(function ($data){
            $this->assertInstanceOf(Token::class, $data);
            $this->token = $data;
        }, function ($error){
            $this->fail();
        }));
    }

    public function testFetchTokenWithInValidCredentials()
    {
        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->never())->method('successHandler');
        $callback->expects($this->once())->method('errorHandler');

        $tokenCredential = new TokenCredential('id', 'secret', false);
        $service = new TokenService(new SymfonyHttpClientAdapter(), $tokenCredential);
        $service->execute($callback);
    }
}

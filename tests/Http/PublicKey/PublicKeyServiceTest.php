<?php

namespace Tm\OrangeMoneySDK\Test\Http\PublicKey;

use PHPUnit\Framework\TestCase;
use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpClientResponse;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;
use Tm\OrangeMoneySDK\Http\PublicKey\PublicKey;
use Tm\OrangeMoneySDK\Http\PublicKey\PublicKeyService;
use Tm\OrangeMoneySDK\Http\Token\Token;

class PublicKeyServiceTest extends TestCase
{
    public function testSuccessPublicKeyFetch()
    {
        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())->method('get')
            ->with('https://api.sandbox.orange-sonatel.com/api/account/v1/publicKeys', [
                'Authorization' => 'Bearer access'
            ])->willReturn(new HttpClientResponse(true, ['key' => 'key', 'keyId' => 'id', 'keyType' => 'type', 'keySize' => 1]));
        $token = new Token('access', 'refresh', false);

        $service = new PublicKeyService($client, $token);
        $service->execute($this->getMockCallback(new PublicKey('key', 'id', 'type', 1)));
    }

    private function getMockCallback(mixed $params, bool $valid = true)
    {
        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        if($valid){
            $callback->expects($this->once())->method('successHandler')->with($params);
            $callback->expects($this->never())->method('errorHandler');
            return $callback;
        }
        $callback->expects($this->never())->method('successHandler');
        $callback->expects($this->once())->method('errorHandler')->with($params);
        return $callback;
    }

}
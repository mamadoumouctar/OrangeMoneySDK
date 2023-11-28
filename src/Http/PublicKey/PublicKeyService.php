<?php

namespace Tm\OrangeMoneySDK\Http\PublicKey;

use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpException;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;
use Tm\OrangeMoneySDK\Http\Token\Token;

class PublicKeyService
{
    private const SUFFIX = '/api/account/v1/publicKeys';
    private HttpClientInterface $client;

    private Token $token;

    public function __construct(HttpClientInterface $client, Token $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function execute(HttpResponseCallbackInterface $callback): void
    {
        try {
            $response = $this->client->get('https://'.$this->token->makeFQDN(self::SUFFIX), header: [
                'Authorization' => 'Bearer ' . $this->token->getAccessToken()
            ]);
            $data = $response->getData();
            if($response->isSuccess()){
                $callback->successHandler(new PublicKey($data['key'], $data['keyId'], $data['keyType'], $data['keySize']));
                return;
            }
            $callback->errorHandler($data);
        }catch (HttpException $e){
            $callback->errorHandler($e->getMessage());
        }
    }
}

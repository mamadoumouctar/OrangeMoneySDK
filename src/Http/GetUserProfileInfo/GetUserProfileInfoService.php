<?php

namespace Tm\OrangeMoneySDK\Http\GetUserProfileInfo;

use Tm\OrangeMoneySDK\Domain\Entity\OmResponseError;
use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;
use Tm\OrangeMoneySDK\Http\Token\Token;

class GetUserProfileInfoService
{
    private const SUFFIX = '/api/eWallet/v1/account';
    private HttpClientInterface $client;

    private Token $token;

    public function __construct(HttpClientInterface $client, Token $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function execute(HttpResponseCallbackInterface $callback, string $msisdn): void
    {
        try {
            $response = $this->client->get('https://'.$this->token->makeFQDN(self::SUFFIX), [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->token->getAccessToken(),
            ], [
                'msisdn' => $msisdn
            ]);
            $data = $response->getData();
            if($response->isSuccess()){
                $callback->successHandler(UserProfileInfo::fromArray($data));
                return;
            }
            $callback->errorHandler(OmResponseError::makeFromArray($data));
        }catch (\Exception $e){
            $callback->errorHandler($e->getMessage());
        }
    }
}

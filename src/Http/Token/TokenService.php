<?php

namespace Tm\OrangeMoneySDK\Http\Token;

use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;

class TokenService
{
    private const SUFFIX = '/oauth/token';
    private readonly HttpClientInterface $client;
    private readonly TokenCredential $credential;

    public function __construct(HttpClientInterface $client, TokenCredential $credential)
    {
        $this->client = $client;
        $this->credential = $credential;
    }

    public function execute(HttpResponseCallbackInterface $callback): void
    {
        try {
            $response = $this->client->post(
                'https://'.$this->credential->makeFQDN(self::SUFFIX),
                header: ['Content-Type' => 'application/x-www-form-urlencoded'],
                body: $this->getRequestBody()
            );
            $data = $response->getData();
            if($response->isSuccess()){
                $callback->successHandler(new Token($data['access_token'], $data['refresh_token'], $this->credential->isProduction));
            }else{
                $callback->errorHandler($data);
            }
            return;
        }catch (\Exception $e){
            $callback->errorHandler($e->getMessage());
        }
    }

    /**
     * @return array<string>
     */
    private function getRequestBody(): array
    {
        return [
            'grant_type' => 'client_credentials',
            'client_id' => $this->credential->client_id,
            'client_secret' => $this->credential->client_secret
        ];
    }
}

<?php

namespace Tm\OrangeMoneySDK\Adapter;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpClientResponse;

class SymfonyHttpClientAdapter implements HttpClientInterface
{

    public function get(string $url, ?array $header = [], ?array $query = []): HttpClientResponse
    {
        $request = HttpClient::create()->request('GET', $url, [
            'headers' => $header,
            'query' => $query,
        ]);
        if($request->getStatusCode() === 200){
            return new HttpClientResponse(true, json_decode($request->getContent(), true));
        }else{
            return new HttpClientResponse(false, "echec");
        }
    }

    public function post(string $url, ?array $header = [], ?array $query = [], string|array $body = []): HttpClientResponse
    {
        $request = HttpClient::create()->request('POST', $url, [
            'headers' => $header,
            'query' => $query,
            'body' => $body
        ]);
        $data = $request->getContent();
        if($request->getStatusCode() === 200){
            return new HttpClientResponse(true, json_decode($data, true));
        }else{
            return new HttpClientResponse(false, $data);
        }
    }
}

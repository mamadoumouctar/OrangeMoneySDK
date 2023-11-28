<?php

namespace Tm\OrangeMoneySDK\Http\io;

interface HttpClientInterface
{
    /**
     * @param string $url
     * @param array<string> $header
     * @param array<string> $query
     * @return HttpClientResponse
     * @throws HttpException
     */
    public function get(string $url, ?array $header = [], ?array $query = []): HttpClientResponse;

    /**
     * @param string $url
     * @param array<string> $header
     * @param array<string> $query
     * @param array<string> $body
     * @return HttpClientResponse
     * @throws HttpException
     */
    public function post(string $url, ?array $header = [], ?array $query = [], array|string $body = ''): HttpClientResponse;
}

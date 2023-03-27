<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FruityviceClient
{
    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    protected function getDefaultOptions(): array
    {
        return [
            'base_uri' => 'https://fruityvice.com/api/',
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
    }

    private function request(string $resource, string $method = 'GET', array $options = []): ResponseInterface
    {
        return $this->httpClient->request(
            $method,
            $resource,
            array_merge($this->getDefaultOptions(), $options)
        );
    }

    public function getFruitAll(): array
    {
        return $this->request('fruit/all')->toArray();
    }
}
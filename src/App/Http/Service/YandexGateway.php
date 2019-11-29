<?php

namespace App\Http\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class YandexGateway implements GatewayInterface
{
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function send(): int
    {
        try {
            $response = $this->client->request('GET', 'https://yandex.ru');
        } catch (TransportExceptionInterface $e) {
            var_dump($e->getMessage());
        }
        try {
            return $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            var_dump($e->getMessage());
        }
    }
}
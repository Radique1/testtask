<?php

declare(strict_types = 1);

namespace App\Service\Http;

use ApiPlatform\Metadata\UrlGeneratorInterface;
use App\Dto\ApiServiceStateDto;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class HttpClientService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function request(ApiServiceStateDto $state): ?array
    {
        $response = $this->httpClient->request(
            $state->getMethod(),
            'http://nginx' . $this->urlGenerator->generate(
                $state->getRouteName(),
                $state->getPathParameters(),
            ),
            [
                'headers' => $this->getHeaders($state->getMethod()),
                'body' => $this->getBody($state->getPayload()),
            ],
        );


        return json_decode($response->getContent(), true);
    }

    private function getBody(array $payload): string
    {
        return json_encode($payload);
    }

    private function getHeaders(string $method): array
    {
        return match ($method) {
            Request::METHOD_PATCH => [
                'Content-Type' => 'application/merge-patch+json',
                'Accept' => 'application/ld+json',
            ],
            default => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ]
        };
    }
}
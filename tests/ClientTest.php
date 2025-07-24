<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests;

use NotKinopoisk\Client;
use NotKinopoisk\Exception\InvalidApiKeyException;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testClientCreation(): void
    {
        $client = new Client('test-api-key');
        
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('test-api-key', $client->getApiKey());
        $this->assertEquals('https://kinopoiskapiunofficial.tech', $client->getBaseUrl());
    }

    public function testClientWithConfig(): void
    {
        $config = [
            'timeout' => 60,
            'headers' => [
                'User-Agent' => 'Test Client'
            ]
        ];
        
        $client = new Client('test-api-key', $config);
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testServicesInitialization(): void
    {
        $client = new Client('test-api-key');
        
        $this->assertInstanceOf(\NotKinopoisk\Services\FilmService::class, $client->films);
        $this->assertInstanceOf(\NotKinopoisk\Services\PersonService::class, $client->persons);
        $this->assertInstanceOf(\NotKinopoisk\Services\UserService::class, $client->users);
        $this->assertInstanceOf(\NotKinopoisk\Services\MediaService::class, $client->media);
    }

    public function testSetBaseUrl(): void
    {
        $client = new Client('test-api-key');
        $client->setBaseUrl('https://test-api.example.com');
        
        $this->assertEquals('https://test-api.example.com', $client->getBaseUrl());
    }
} 
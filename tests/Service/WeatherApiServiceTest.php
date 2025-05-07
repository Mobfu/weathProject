<?php

namespace App\Tests\Service;

use App\Service\WeatherApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherApiServiceTest extends TestCase
{
    public function testGetWeatherReturnsCurrentWeather(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')->willReturn([
            'current_weather' => [
                'temperature' => 20,
                'windspeed' => 5,
                'time' => '2025-05-07T12:00',
            ]
        ]);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        $weatherService = new WeatherApiService($mockHttpClient);

        $result = $weatherService->getWeather('paris');

        $this->assertArrayHasKey('current_weather', $result);
        $this->assertEquals(20, $result['current_weather']['temperature']);
    }
}

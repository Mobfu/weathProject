<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApiService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getWeather(string $city): array
    {
        // 示例城市经纬度，可以使用地理编码服务转换城市为坐标
        $coordinates = [
            'paris' => ['lat' => 48.8566, 'lon' => 2.3522],
            'lyon' => ['lat' => 45.75, 'lon' => 4.85],
            'london' => ['lat' => 51.5072, 'lon' => -0.1276],
        ];

        if (!isset($coordinates[strtolower($city)])) {
            return ['error' => 'City not supported'];
        }

        $lat = $coordinates[strtolower($city)]['lat'];
        $lon = $coordinates[strtolower($city)]['lon'];

        $response = $this->client->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => [
                'latitude' => $lat,
                'longitude' => $lon,
                'current_weather' => true,
            ]
        ]);

        return $response->toArray();
    }
}

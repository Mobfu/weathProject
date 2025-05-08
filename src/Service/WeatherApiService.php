<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * WeatherApiService
 *
 * A service that communicates with the Open-Meteo API to fetch current weather data
 * for predefined cities using their latitude and longitude coordinates.
 */
class WeatherApiService
{
    private HttpClientInterface $client;

    /**
     * Constructor with dependency injection of Symfony HTTP client.
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Fetch current weather data for a specific city using Open-Meteo API.
     *
     * @param string $city The name of the city (e.g. "paris", "lyon", "london")
     * @return array Returns current weather data or an error if city is not supported
     */
    public function getWeather(string $city): array
    {
        // Predefined coordinates for supported cities
        $coordinates = [
            'paris' => ['lat' => 48.8566, 'lon' => 2.3522],
            'lyon' => ['lat' => 45.75, 'lon' => 4.85],
            'london' => ['lat' => 51.5072, 'lon' => -0.1276],
        ];

        // Return error if city is not in the supported list
        if (!isset($coordinates[strtolower($city)])) {
            return ['error' => 'City not supported'];
        }

        $lat = $coordinates[strtolower($city)]['lat'];
        $lon = $coordinates[strtolower($city)]['lon'];

        // Make a GET request to Open-Meteo's forecast endpoint
        $response = $this->client->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => [
                'latitude' => $lat,
                'longitude' => $lon,
                'current_weather' => true,
            ]
        ]);

        // Convert response to associative array and return it
        return $response->toArray();
    }
}

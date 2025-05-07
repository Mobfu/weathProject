<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherControllerTest extends WebTestCase
{
    public function testPostWeatherSuccess(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/weather', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'city' => 'Paris',
            'temperature' => 18.5,
            'windSpeed' => 4.2,
            'date' => '2025-05-08T12:00:00'
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals('Paris', $responseData['city']);
    }
    public function testPostWeatherMissingDate(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/weather', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'city' => 'Lyon',
            'temperature' => 17.5,
            'windSpeed' => 3.1
        ]));

        $this->assertResponseStatusCodeSame(400);
    }

}

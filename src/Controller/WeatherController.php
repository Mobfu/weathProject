<?php

namespace App\Controller;

use App\Entity\Weather;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\WeatherApiService;

#[Route('/api/weather')]
class WeatherController extends AbstractController
{
    #[Route('', name: 'weather_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $weatherData = $em->getRepository(Weather::class)->findAll();
        return $this->json($weatherData);
    }

    #[Route('', name: 'weather_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // ✅ 输出内容调试
        if (!isset($data['date'])) {
            return $this->json(['error' => 'Missing date field'], 400);
        }

        try {
            $date = new \DateTimeImmutable($data['date']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid date format'], 400);
        }

        $weather = new Weather();
        $weather->setCity($data['city'] ?? '');
        $weather->setTemperature((float)($data['temperature'] ?? 0));
        $weather->setWindSpeed((float)($data['windSpeed'] ?? 0));
        $weather->setDate($date);
        $weather->setDescription($data['description'] ?? ''); // 如果有这个字段

        $em->persist($weather);
        $em->flush();

        return $this->json($weather, 201);
    }


    #[Route('/{id}', name: 'weather_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $weather = $em->getRepository(Weather::class)->find($id);

        if (!$weather) {
            return $this->json(['message' => 'Weather not found'], 404);
        }

        return $this->json($weather);
    }

    #[Route('/{id}', name: 'weather_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $weather = $em->getRepository(Weather::class)->find($id);

        if (!$weather) {
            return $this->json(['message' => 'Weather not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $weather->setCity($data['city'] ?? $weather->getCity());
        $weather->setTemperature($data['temperature'] ?? $weather->getTemperature());
        $weather->setDescription($data['description'] ?? $weather->getDescription());

        $em->flush();

        return $this->json($weather);
    }

    #[Route('/{id}', name: 'weather_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $weather = $em->getRepository(Weather::class)->find($id);

        if (!$weather) {
            return $this->json(['message' => 'Weather not found'], 404);
        }

        $em->remove($weather);
        $em->flush();

        return $this->json(['message' => 'Weather deleted']);
    }

    #[Route('/realtime/{city}', name: 'weather_realtime', methods: ['GET'])]
    public function getRealtimeWeather(
        string $city,
        WeatherApiService $weatherApiService,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = $weatherApiService->getWeather($city);

        if (!isset($data['current_weather'])) {
            return $this->json(['message' => 'Weather API error'], 500);
        }

        $weatherData = $data['current_weather'];

        $weather = new Weather();
        $weather->setCity($city);
        $weather->setTemperature($weatherData['temperature']);
        $weather->setWindSpeed($weatherData['windspeed']);
        $weather->setDate(new \DateTimeImmutable($weatherData['time']));
        $weather->setDescription('Auto-fetched from Open-Meteo');

        $em->persist($weather);
        $em->flush();

        return $this->json($weather, 201);
    }

}

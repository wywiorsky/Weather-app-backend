<?php

namespace App\Controller;

use App\Services\HistoryService;
use App\Services\OpenWeatherService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class weatherController extends FOSRestController
{
    private $historyService;
    private $openWeatherService;

    public function __construct(
        HistoryService $historyService,
        OpenWeatherService $openWeatherService
    ) {
        $this->historyService = $historyService;
        $this->openWeatherService = $openWeatherService;
    }

/**
 * @Rest\Get("/weather")
 */
    public function getWeather(Request $request): View
    {
        $weather = $this->openWeatherService->getWeatherByCordinates($request->query);
        $this->historyService->save($weather);
        return View::create($weather, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/history")
     */
    public function getHistory(Request $request): View
    {

        $weather = $this->historyService->get($request->query);
        return View::create($weather, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/stats")
     */
    public function getStats(): View
    {

        $weather = $this->historyService->getStats();
        return View::create($weather, Response::HTTP_OK);
    }
}

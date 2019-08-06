<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Unirest\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class OpenWeatherService
{

    private $url;
    private $apiKey;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container; 
        $this->url = $container->getParameter('openWeather')['url'];
        $this->apiKey = $container->getParameter('openWeather')['ApiKey'];
    }

    public function getWeatherByCordinates(ParameterBag $parameterBag) : array
    {
        $time_start = microtime(true); 

        $method = 'weather';
        $url = sprintf('%s/%s', $this->url, $method);
        $headers = [
            'Accept' => 'application/json'
        ];

        $query = [

            'lat' =>  $parameterBag->getInt('lat', 1),
            'lon' =>  $parameterBag->getInt('lng', 1),
            'units' => 'metric',
            'lang' => 'pl',
            'APPID' => $this->apiKey,

        ];

        $response = Request::get($url, $headers, $query);

        if ($response->code == 200) {

            $weather = json_decode($response->raw_body,1);
            $weather['time'] = (microtime(true) - $time_start);

            return $weather;
        }

        throw new HttpException(404, "Api response error");
    }

}

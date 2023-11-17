<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] ?string $format,
        #[MapQueryParameter] bool $twig = false,
        WeatherUtil $util,
    ): Response
    {
        
        $weathers = $util->getWeatherForCountryAndCity($country, $city);
        if(!isset($format) || $format == 'JSON'){

            if($twig){
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'weathers' => $weathers,
                ]);                
            }

            return $this->json([
                'weathers' => array_map(fn(Weather $w) => [
                    'date' => $w->getDate()->format('Y-m-d'),
                    'celsius' => $w->getCelsius(),
                    'fahrenheit' => $w->getFahrenheit(),
                ], $weathers),
            ]);

        }elseif($format == 'CSV'){

            if ($twig){
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'weathers' => $weathers,
                ]);
                
            }
            $weathers_csv = ['city', 'country', 'date', 'celsius\n'];
            foreach($weathers as $w){
                $weathers_csv[] = $city;
                $weathers_csv[] = $country;
                $weathers_csv[] = $w->getDate()->format('Y-m-d');
                $weathers_csv[] = $w->getCelsius();
                $weathers_csv[] = $w->getFahrenheit() . '\n';
            }
            $weathers_csv = implode(',', $weathers_csv);
            return new Response($weathers_csv, 200, [
                //'Content-Type' => 'text/csv',
            ]);

        }
           
    }
}

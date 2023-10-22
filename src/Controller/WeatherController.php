<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\WeatherRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather')]
    public function city(Location $location, WeatherRepository $repository): Response
    {
        $weathers = $repository->findByLocation($location);
        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'weathers' => $weathers
        ]);
    }
}

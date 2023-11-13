<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Weather;
use App\Entity\Location;
use App\Repository\WeatherRepository;
use App\Repository\LocationRepository;

class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherRepository $weatherRepository,
    )
    {
    }

    /**
     * @return Weather[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        $weathers = $this->weatherRepository->findByLocation($location);
        return $weathers;
    }

    /**
     * @return Weather[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        $weathers = $this->getWeatherForLocation($location);

        return $weathers;
    }
}

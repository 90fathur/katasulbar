<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    public const CACHE_KEY = 'sulbar_realtime_weather_v1';

    public const CACHE_TTL_SECONDS = 900; // 15 minutes

    /**
     * Regencies of West Sulawesi with coordinates.
     *
     * @return array<int, array{name: string, lat: float, lon: float, is_capital: bool}>
     */
    public static function regencies(): array
    {
        return [
            ['name' => 'Mamuju', 'lat' => -2.677, 'lon' => 118.889, 'is_capital' => true],
            ['name' => 'Majene', 'lat' => -3.540, 'lon' => 118.971, 'is_capital' => false],
            ['name' => 'Polewali Mandar', 'lat' => -3.432, 'lon' => 119.343, 'is_capital' => false],
            ['name' => 'Mamasa', 'lat' => -2.933, 'lon' => 119.380, 'is_capital' => false],
            ['name' => 'Pasangkayu', 'lat' => -1.189, 'lon' => 119.385, 'is_capital' => false],
            ['name' => 'Mamuju Tengah', 'lat' => -2.043, 'lon' => 119.336, 'is_capital' => false],
        ];
    }

    /**
     * Get realtime weather data for West Sulawesi.
     *
     * @return array<string, mixed>
     */
    public static function getSulbarWeather(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            return self::fetchRealtimeWeather();
        });
    }

    /**
     * Force refresh the cached weather data.
     *
     * @return array<string, mixed>
     */
    public static function refresh(): array
    {
        Cache::forget(self::CACHE_KEY);

        return self::getSulbarWeather();
    }

    /**
     * Fetch from Open-Meteo API.
     *
     * @return array<string, mixed>
     */
    protected static function fetchRealtimeWeather(): array
    {
        $regencies = self::regencies();
        $lats = implode(',', array_column($regencies, 'lat'));
        $lons = implode(',', array_column($regencies, 'lon'));

        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lats}&longitude={$lons}&current_weather=true&timezone=Asia%2FMakassar";

        try {
            $response = Http::withoutVerifying()->timeout(5)->get($url);

            if ($response->successful()) {
                $data = $response->json();

                $results = is_array($data) && isset($data[0]) ? $data : [$data];
                $regencyReports = [];

                foreach ($regencies as $idx => $reg) {
                    $item = $results[$idx]['current_weather'] ?? null;
                    $temp = isset($item['temperature']) ? round((float) $item['temperature']) : 29;
                    $wcode = (int) ($item['weathercode'] ?? 1);
                    $condition = self::translateWeatherCode($wcode, (bool) ($item['is_day'] ?? 1));

                    $regencyReports[] = [
                        'name' => $reg['name'],
                        'is_capital' => $reg['is_capital'],
                        'temperature' => $temp,
                        'temp_label' => "{$temp}°C",
                        'condition' => $condition['text'],
                        'icon' => $condition['icon'],
                        'color' => $condition['color'],
                        'windspeed' => isset($item['windspeed']) ? (float) $item['windspeed'] : 0,
                    ];
                }

                $primary = $regencyReports[0] ?? self::defaultPrimary();

                return [
                    'temperature' => $primary['temperature'],
                    'temp_label' => $primary['temp_label'],
                    'condition' => $primary['condition'],
                    'icon' => $primary['icon'],
                    'color' => $primary['color'],
                    'location' => 'Mamuju, Sulawesi Barat',
                    'updated_at' => now('Asia/Makassar')->format('H:i').' WITA',
                    'regencies' => $regencyReports,
                    'is_live' => true,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Weather API fetch failed, using fallback: '.$e->getMessage());
        }

        return self::fallbackData();
    }

    /**
     * Map WMO code to condition text and FontAwesome icon.
     *
     * @return array{text: string, icon: string, color: string}
     */
    public static function translateWeatherCode(int $code, bool $isDay = true): array
    {
        return match (true) {
            $code === 0 => [
                'text' => 'Cerah',
                'icon' => $isDay ? 'fas fa-sun' : 'fas fa-moon',
                'color' => '#F59E0B',
            ],
            in_array($code, [1, 2]) => [
                'text' => 'Cerah Berawan',
                'icon' => $isDay ? 'fas fa-cloud-sun' : 'fas fa-cloud-moon',
                'color' => '#38BDF8',
            ],
            $code === 3 => [
                'text' => 'Berawan',
                'icon' => 'fas fa-cloud',
                'color' => '#94A3B8',
            ],
            in_array($code, [45, 48]) => [
                'text' => 'Berkabut',
                'icon' => 'fas fa-smog',
                'color' => '#94A3B8',
            ],
            in_array($code, [51, 53, 55, 56, 57]) => [
                'text' => 'Gerimis',
                'icon' => 'fas fa-cloud-rain',
                'color' => '#60A5FA',
            ],
            in_array($code, [61, 63, 65, 80, 81]) => [
                'text' => 'Hujan',
                'icon' => 'fas fa-cloud-showers-heavy',
                'color' => '#3B82F6',
            ],
            in_array($code, [95, 96, 99]) => [
                'text' => 'Hujan Petir',
                'icon' => 'fas fa-cloud-bolt',
                'color' => '#A855F7',
            ],
            default => [
                'text' => 'Cerah Berawan',
                'icon' => 'fas fa-cloud-sun',
                'color' => '#38BDF8',
            ],
        };
    }

    /**
     * @return array<string, mixed>
     */
    protected static function defaultPrimary(): array
    {
        return [
            'name' => 'Mamuju',
            'is_capital' => true,
            'temperature' => 29,
            'temp_label' => '29°C',
            'condition' => 'Cerah Berawan',
            'icon' => 'fas fa-cloud-sun',
            'color' => '#38BDF8',
            'windspeed' => 8,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function fallbackData(): array
    {
        $primary = self::defaultPrimary();
        $regencies = [];
        foreach (self::regencies() as $r) {
            $temp = $r['name'] === 'Mamasa' ? 24 : 29;
            $regencies[] = [
                'name' => $r['name'],
                'is_capital' => $r['is_capital'],
                'temperature' => $temp,
                'temp_label' => "{$temp}°C",
                'condition' => 'Cerah Berawan',
                'icon' => 'fas fa-cloud-sun',
                'color' => '#38BDF8',
                'windspeed' => 6,
            ];
        }

        return [
            'temperature' => $primary['temperature'],
            'temp_label' => $primary['temp_label'],
            'condition' => $primary['condition'],
            'icon' => $primary['icon'],
            'color' => $primary['color'],
            'location' => 'Mamuju, Sulawesi Barat',
            'updated_at' => now('Asia/Makassar')->format('H:i').' WITA',
            'regencies' => $regencies,
            'is_live' => false,
        ];
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * "Today in Tobago" strip. Open-Meteo needs no API key; the result is cached for
 * 30 minutes and any failure returns null so the page never depends on it.
 */
class Weather
{
    // Crown Point, Tobago.
    protected const LAT = 11.15;

    protected const LON = -60.84;

    protected const CODES = [
        0 => ['Clear sky', 'sun'], 1 => ['Mostly sunny', 'sun'], 2 => ['Partly cloudy', 'cloud-sun'], 3 => ['Overcast', 'cloud'],
        45 => ['Hazy', 'cloud'], 48 => ['Hazy', 'cloud'], 51 => ['Light drizzle', 'cloud-rain'], 53 => ['Drizzle', 'cloud-rain'], 55 => ['Drizzle', 'cloud-rain'],
        61 => ['Light showers', 'cloud-rain'], 63 => ['Showers', 'cloud-rain'], 65 => ['Heavy rain', 'cloud-rain'], 80 => ['Passing showers', 'cloud-rain'], 81 => ['Showers', 'cloud-rain'], 82 => ['Heavy showers', 'cloud-rain'],
        95 => ['Thunderstorms', 'cloud-rain'],
    ];

    /** @return array{temp: int, feels: int, wind: int, code: int, label: string, icon: string, sea: int|null, sunrise: string, sunset: string}|null */
    public static function today(): ?array
    {
        if (app()->runningUnitTests()) {
            return null;
        }

        $cached = Cache::get('weather.today');
        if ($cached !== null) {
            return $cached === 'unavailable' ? null : $cached;
        }
        $result = (function () {
            try {
                $w = Http::timeout(4)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => self::LAT, 'longitude' => self::LON,
                    'current' => 'temperature_2m,apparent_temperature,weather_code,wind_speed_10m',
                    'daily' => 'sunrise,sunset', 'timezone' => config('site.timezone'), 'forecast_days' => 1,
                ])->throw()->json();
                $sea = rescue(fn () => Http::timeout(4)->get('https://marine-api.open-meteo.com/v1/marine', [
                    'latitude' => self::LAT, 'longitude' => self::LON, 'current' => 'sea_surface_temperature', 'timezone' => config('site.timezone'),
                ])->json('current.sea_surface_temperature'), null, false);
                $code = (int) ($w['current']['weather_code'] ?? 0);
                [$label, $icon] = self::CODES[$code] ?? ['Island weather', 'sun'];

                return [
                    'temp' => (int) round($w['current']['temperature_2m']),
                    'feels' => (int) round($w['current']['apparent_temperature']),
                    'wind' => (int) round($w['current']['wind_speed_10m']),
                    'code' => $code,
                    'label' => $label,
                    'icon' => $icon,
                    'sea' => $sea !== null ? (int) round($sea) : null,
                    'sunrise' => substr($w['daily']['sunrise'][0] ?? '', 11, 5),
                    'sunset' => substr($w['daily']['sunset'][0] ?? '', 11, 5),
                ];
            } catch (\Throwable) {
                return null;
            }
        })();
        // Null is never cached by Cache::remember, so a failing API would be retried (8s of timeouts) on every page.
        Cache::put('weather.today', $result ?? 'unavailable', now()->addMinutes($result ? 30 : 10));

        return $result;
    }
}

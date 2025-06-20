<?php

namespace App\Livewire\Components\Dashboard\Support;

use Illuminate\Support\Carbon;

class DateRangeHelper
{
    public static function getLastNDays(int $days): array
    {
        $endDate = Carbon::now()->endOfDay();
        return [
            'start' => $endDate->copy()->subDays($days - 1)->startOfDay(),
            'end' => $endDate,
        ];
    }

    public static function getPreviousPeriod(Carbon $startDate, Carbon $endDate): array
    {
        $duration = $startDate->diffInDays($endDate);
        return [
            'start' => $startDate->copy()->subDays($duration + 1),
            'end' => $startDate->copy()->subDay(),
        ];
    }

    public static function generateDateSeries(Carbon $startDate, Carbon $endDate): array
    {
        $dates = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
}

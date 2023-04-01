<?php

namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DateHelper
{
    public static function randomDate(string $minStartDate, string $maxEndDate, $format = 'Y-m-d') {
        $startDate  = Carbon::create($minStartDate);
        $endDate    = Carbon::create($maxEndDate);
        $diff       = $startDate->diffInDays($endDate);

        return $startDate->copy()->addDays(rand(1, $diff))->format($format);
    }

    public static function yearsOptionsByStartEnd(Carbon $start, Carbon $end): Collection|null {
        $interval = $start->diffAsCarbonInterval($end);
        if(0 === $interval->years) {
            $today = Carbon::today(config('app.timezone'));
            return collect([$today->year => $today->year]);
        }
        else {
            $years[$start->year] = $start->year;
            $i = 0;
            while( $i < $interval->years) {
                $date = $start->copy()->addYear();
                $years[$date->year] = $date->year;
                $i++;
            }
            return collect($years);
        }
    }

    public static function monthOptionsByStartEnd(Carbon $start, Carbon $end): Collection|null {
        $interval = $start->diffAsCarbonInterval($end);
        if(0 === $interval->months) {
            $today = Carbon::today(config('app.timezone'));
            return collect([$today->month => $today->monthName]);
        }
        else {
            $months[$start->month] = $start->getTranslatedMonthName();
            $i = 0;
            while( $i < $interval->years) {
                $date = $start->copy()->addMonth();
                $months[$date->month] = $date->getTranslatedMonthName();
                $i++;
            }
            return collect($months);
        }
    }

    public static function yearOptions(Collection $data, string $field): Collection|null
    {
        return $data
            ->map(fn(Model $item) => $item->$field->format('Y'))
            ->sort()
            ->unique()
            ->keyBy(fn($item) => $item)
            ->prepend('wähle Jahr',null);
    }

    public static function monthOptions(Collection $data, string $field): Collection|null
    {
        return $data
            ->sortBy(fn($item) => $item->id)
            ->keyBy(fn($item) => $item->created_at->format('m'))
            ->map(fn(Model $item) => $item->created_at->getTranslatedMonthName())
            ->unique()
            ->prepend('wähle Monat',null);
    }
}

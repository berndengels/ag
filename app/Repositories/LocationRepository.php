<?php

namespace App\Repositories;

use App\Models\Location;
class LocationRepository
{

    public static function repair()
    {
        Location::whereRaw("name LIKE '%,%'")->get()->each(function (Location $location) {
            $arr = explode(',', $location->name, 2);
            if(count($arr) > 1) {
                $data = [
                    'name' => $arr[0],
                    'extra' => $arr[1]
                ];
                $location->update($data);
            }
        });
    }

    public static function setJcFound()
    {
        Location::has('jobcenter')
            ->get()->each(function(Location $location) {
                $location->update(['found_jc' => 1]);
            });

    }

    public static function setAaFound()
    {
        Location::has('arbeitsagentur')
            ->get()->each(function(Location $location) {
                $location->update(['found_aa' => 1]);
            });
    }
}

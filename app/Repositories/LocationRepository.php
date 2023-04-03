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
}

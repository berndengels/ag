<?php

namespace App\Repositories;

use App\Models\EmploymentAgency;
use App\Repositories\Traits\ResultParser;

class ArbeitsamtRepository
{
    use ResultParser;

    public static function repairByHtml($nullField = 'fon')
    {
        $affected = 0;
        EmploymentAgency::whereNull($nullField)->get()->each(function (EmploymentAgency $item) use (&$affected) {
            if($item->response) {
                $data = $this->parse($item->response);
                if(!isset($data['error']) && $item->update($data)) {
                    $affected++;
                }
            }
        });
        return $affected;
    }

    public function repairByTable()
    {

    }
}

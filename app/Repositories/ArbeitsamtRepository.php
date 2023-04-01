<?php

namespace App\Repositories;

use App\Models\Arbeitsagentur;
use App\Repositories\Traits\ResultParser;

class ArbeitsamtRepository
{
    use ResultParser;

    public static function repairByHtml($nullField = 'fon')
    {
        $affected = 0;
        Arbeitsagentur::whereNull($nullField)->get()->each(function (Arbeitsagentur $item) use (&$affected) {
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

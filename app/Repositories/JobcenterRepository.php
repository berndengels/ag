<?php

namespace App\Repositories;

use App\Models\JobCentre;
use App\Repositories\Traits\ResultParser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class JobcenterRepository
{
    use ResultParser;
    public static function repairByHtml($nullField = 'fon')
    {
        $affected = 0;
        JobCentre::whereNull($nullField)->get()->each(function (JobCentre $item) use (&$affected) {
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

    public static function sanitizeHtmlEntiies()
    {
        $jobcenter  = new JobCentre();
        $table      = $jobcenter->getTable();
        /**
         * @var Collection $columns
         */
        $columns    = collect(Schema::getColumnListing($table))
            ->keyBy(fn($item) => $item)
            ->except(['id','customer_postcode','response','created_at','updated_at'])
            ->values()
        ;

        $data = JobCentre::all()->each(function (JobCentre $item) use  ($columns) {
            $data = $columns
                ->keyBy(fn($k) => $k)
                ->map(function($d) use ($item) {
                    return trim(html_entity_decode($item->$d));
                })->toArray();
            $item->update($data);
        });
    }

}

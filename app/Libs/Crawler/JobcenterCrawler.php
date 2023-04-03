<?php

namespace App\Libs\Crawler;

use App\Models\Jobcenter;

class JobcenterCrawler extends Crawler
{
    protected $model = Jobcenter::class;
    protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&volltext=%CITY%&plz=%PLZ%';
}

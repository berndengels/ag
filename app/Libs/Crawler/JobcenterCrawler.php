<?php

namespace App\Libs\Crawler;

use App\Models\JobCentre;

class JobcenterCrawler extends Crawler
{
    protected $model = JobCentre::class;
//    protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&volltext=%CITY%&plz=%PLZ%';
	protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&plz=%PLZ%';
}

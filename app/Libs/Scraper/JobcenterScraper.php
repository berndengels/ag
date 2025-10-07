<?php

namespace App\Libs\Scraper;

use App\Models\Jobcenter;

class JobcenterScraper extends Scraper
{
    protected $model = Jobcenter::class;
	protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&plz=%PLZ%';
}

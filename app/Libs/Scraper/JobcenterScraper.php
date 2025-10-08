<?php

namespace App\Libs\Scraper;

use App\Models\JobCentre;

class JobcenterScraper extends Scraper
{
    protected $model = JobCentre::class;
	protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&plz=%PLZ%';
}

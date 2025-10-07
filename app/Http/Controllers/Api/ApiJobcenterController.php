<?php

namespace App\Http\Controllers\Api;

use App\Libs\Scraper\JobcenterScraper;
use App\Models\Jobcenter;

class ApiJobcenterController extends ApiScraperController
{
	protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&plz=%PLZ%';
    protected $scraper = JobcenterScraper::class;
	protected $model = Jobcenter::class;
    protected $foundField = 'found_jc';
}

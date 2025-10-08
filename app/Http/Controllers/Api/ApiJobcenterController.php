<?php

namespace App\Http\Controllers\Api;

use App\Libs\Scraper\JobcenterScraper;
use App\Models\JobCentre;

class ApiJobcenterController extends ApiScraperController
{
	protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&plz=%PLZ%';
    protected $scraper = JobcenterScraper::class;
	protected $model = JobCentre::class;
    protected $foundField = 'found_jc';
}

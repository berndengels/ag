<?php

namespace App\Http\Controllers\Api;

use App\Libs\Crawler\JobcenterCrawler;

class ApiJobcenterCrawlerController extends ApiCrawlerController
{
    protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&volltext=%CITY%&&plz=%PLZ%';
    protected $crawler = JobcenterCrawler::class;
    protected $relation = 'jobcenter';
    protected $foundField = 'found_jc';
}

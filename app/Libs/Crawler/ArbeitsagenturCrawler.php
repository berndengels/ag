<?php

namespace App\Libs\Crawler;

use App\Models\EmploymentAgency;

class ArbeitsagenturCrawler extends Crawler
{
    protected $model = EmploymentAgency::class;
    protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=arbeitsagenturen&plz=%PLZ%';
}

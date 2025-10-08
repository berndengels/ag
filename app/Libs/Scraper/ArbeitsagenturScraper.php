<?php

namespace App\Libs\Scraper;

use App\Models\EmploymentAgency;

class ArbeitsagenturScraper extends Scraper
{
    protected $model = EmploymentAgency::class;
    protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=arbeitsagenturen&plz=%PLZ%';
}

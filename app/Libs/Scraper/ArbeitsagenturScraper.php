<?php

namespace App\Libs\Scraper;

use App\Models\Arbeitsagentur;

class ArbeitsagenturScraper extends Scraper
{
    protected $model = Arbeitsagentur::class;
    protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=arbeitsagenturen&plz=%PLZ%';
}

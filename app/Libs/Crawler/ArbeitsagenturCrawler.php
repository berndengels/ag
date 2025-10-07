<?php

namespace App\Libs\Crawler;

use App\Models\Arbeitsagentur;

class ArbeitsagenturCrawler extends Crawler
{
    protected $model = Arbeitsagentur::class;
    protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=arbeitsagenturen&plz=%PLZ%';
}

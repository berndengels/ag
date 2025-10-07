<?php

namespace App\Http\Controllers\Api;

use App\Libs\Scraper\ArbeitsagenturScraper;
use App\Models\Arbeitsagentur;

class ApiArbeitsagenturController extends ApiScraperController
{
	protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=arbeitsagenturen&plz=%PLZ%';
    protected $scraper = ArbeitsagenturScraper::class;
    protected $model = Arbeitsagentur::class;
    protected $foundField = 'found_aa';
}

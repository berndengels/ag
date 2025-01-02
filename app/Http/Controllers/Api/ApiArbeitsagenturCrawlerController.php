<?php

namespace App\Http\Controllers\Api;

use App\Libs\Crawler\ArbeitsagenturCrawler;

class ApiArbeitsagenturCrawlerController extends ApiCrawlerController
{
//    protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=arbeitsagenturen&volltext=%CITY%&plz=%PLZ%';
	protected $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=arbeitsagenturen&plz=%PLZ%';
    protected $crawler = ArbeitsagenturCrawler::class;
    protected $relation = 'arbeitsagentur';
    protected $foundField = 'found_aa';
}

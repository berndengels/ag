<?php

namespace App\Libs\Crawler;

use App\Libs\Crawler\Observer\LinkObserver;
use Eloquent;
use Exception;
use App\Models\Location;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use App\Libs\Crawler\Observer\Observer;
use App\Repositories\Traits\ResultParser;
use Spatie\Crawler\Crawler as SpatieCrawler;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;

class Crawler implements ICrawler
{
    use ResultParser;
    /**
     * @var string
     */
    protected $baseUrl = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen';

	private $usleep = 1000;
	private $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:143.0) Gecko/20100101 Firefox/143.0';
	/**
	 * @var Observer
	 */
	protected $observer;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var Eloquent
     */
    protected $model;
    /**
     * @var Eloquent
     */
    protected $entity;
    protected $image;
	/**
	 * @var Browsershot
	 */
	protected $browsershot;

    /**
     * @param Location $location
     */
    public function __construct(
        protected Location|null $location = null
    ) {}

    /**
     * @param Location $location
     * @return $this
     */

    public function run():self {
        if(!$this->location) {
            throw new Exception('location object must be set!');
        }

//        $this->url = str_replace(['%CITY%','%PLZ%'], [urlencode($this->location->name), $this->location->zipcode], $this->url);
		$this->url = str_replace('%PLZ%', $this->location->zipcode, $this->url);
//        $this->observer = new Observer($this->model, $this->location);
		$this->observer = new LinkObserver($this->model, $this->location);
		$this->crawle();

		return $this;
    }

	private function crawle()
	{
		usleep($this->usleep);

		$options = [
			RequestOptions::ALLOW_REDIRECTS => true,
			RequestOptions::TIMEOUT => 30
		];
/*
		$image = Storage::disk('browsershots')->path($this->location->zipcode . '.jpg');

		$this->browsershot = Browsershot::url($this->url)
			->newHeadless()
			->dismissDialogs()
			->setScreenshotType('jpeg', 70)
			->waitForSelector('.dienststellen-result a')
			->click('.dienststellen-result a')
			->select('.form-check #arbeitsagenturen')
		;
		$html = $this->browsershot->bodyHtml();
		Storage::disk('browsershots')
			->put($this->location->zipcode. '.html', $html)
		;
//		$this->browsershot->save($image);
*/
		SpatieCrawler::create($options)
			->setDefaultScheme('https')
			->setCrawlObserver($this->observer)
			->setCrawlProfile(new CrawlInternalUrls($this->baseUrl))
			->setUserAgent($this->userAgent)
			->setConcurrency(1)
			->setTotalCrawlLimit(1)
			->setMaximumDepth(0)
			->setDelayBetweenRequests(200)
			->executeJavaScript()
			->startCrawling($this->url)
		;

		$this->entity = $this->model::whereCustomerPostcode($this->location->zipcode)->get()->last();
//		$this->image = $this->browsershot->screenshot();
	}

    /**
     * @return mixed
     */
    public function getEntity(): object|null
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location):self
    {
        $this->location = $location;
        return $this;
    }
}

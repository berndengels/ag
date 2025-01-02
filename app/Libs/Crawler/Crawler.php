<?php

namespace App\Libs\Crawler;

use Eloquent;
use Exception;
use App\Models\Location;
use GuzzleHttp\RequestOptions;
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
        $this->observer = new Observer($this->model, $this->location);
		$this->crawle();

		return $this;
    }

	private function crawle()
	{
		$options = [
			RequestOptions::ALLOW_REDIRECTS => true,
			RequestOptions::TIMEOUT => 30
		];
		SpatieCrawler::create($options)
			->executeJavaScript()
			->setTotalCrawlLimit(1)
			->setDelayBetweenRequests(200)
			->setCrawlObserver($this->observer)
			->setCrawlProfile(new CrawlInternalUrls($this->baseUrl))
			->startCrawling($this->url)
		;

		$this->entity = $this->model::whereCustomerPostcode($this->location->zipcode)->get()->last();
	}

	public function setPostcodeUrl()
	{
/*
		[$scheme, $host, $path, $query] = array_values(parse_url($this->url));
		$query = preg_replace('/&volltext=[^&]+/i', '', $query);
		$this->url = "{$scheme}://{$host}{$path}?{$query}";
*/
		return $this;
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

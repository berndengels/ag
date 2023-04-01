<?php

namespace App\Libs\Crawler;

use App\Models\Location;
use App\Models\ZipCoordinate;
use Eloquent;
use Exception;
use GuzzleHttp\RequestOptions;
use App\Libs\Crawler\Observer\Observer;
use App\Repositories\Traits\ResultParser;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
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
        $url = str_replace('%PLZ%', $this->location->zipcode, $this->url);
        $options = [
            RequestOptions::ALLOW_REDIRECTS => true,
            RequestOptions::TIMEOUT => 30
        ];
        $observer = new Observer($this->model, $this->location->zipcode);
        SpatieCrawler::create($options)
            ->executeJavaScript()
            ->setTotalCrawlLimit(1)
            ->setDelayBetweenRequests(200)
            ->setCrawlObserver($observer)
            ->setCrawlProfile(new CrawlInternalUrls($this->baseUrl))
            ->startCrawling($url)
        ;
        $this->entity = $this->model::whereCustomerPostcode($this->location->zipcode)->first();
        return $this;
    }

    /**
     * @param ZipCoordinate $location
     * @return $this
     */
    public function update():self
    {
        if(!$this->location) {
            throw new Exception('location object must be set!');
        }
        if($this->entity && $this->entity->response) {
            try {
                $data = $this->parse($this->entity->response);
                $this->entity->update($data);
                $this->entity->refresh();
            } catch (Exception $e) {
                Log::error('update ' .$this->location->zipcode .': '. $e->getMessage());
            }
        } else {
            Log::error('update no entity: ' . $this->location->zipcode);
        }
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

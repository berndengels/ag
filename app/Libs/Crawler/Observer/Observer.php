<?php

namespace App\Libs\Crawler\Observer;

use App\Models\CrawlerLog;
use Eloquent;
use Exception;
use App\Models\Location;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Exception\RequestException;
use App\Repositories\Traits\ResultParser;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Observer extends CrawlObserver
{
    use ResultParser;

    /**
     * @var Symfony\Component\DomCrawler\Crawler
     */
    public $result;
    private $entry;
	private $log;
	private $url;
	private $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:143.0) Gecko/20100101 Firefox/143.0';

    public function __construct(
        protected string $model,
        protected Location $location
    ) {
		$this->log = Log::channel('crawler');
	}

    /**
     * Called when the crawler will crawl the url.
     *
     * @param UriInterface $url
     */
    public function willCrawl(UriInterface $url, ?string $linkText): void
    {
		$this->url = $url;
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param UriInterface $url
     * @param ResponseInterface $response
     * @param UriInterface|null $foundOnUrl
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null,
		?string $linkText = null,
	):void {
		$this->url = $url;
        $content = $response->getBody()->getContents();
        $crawler = new DomCrawler($content);
        try {
			$link = $crawler->filter('.dienststellen-result a')->first()->attr('href');
			CrawlerLog::create([
				'url'	=> $this->url,
				'link'	=> $link,
				'content'	=> $content,
			]);
			$this->log->error('link: ' . $link);
/*
			if($this->result->count() > 0) {
				$results->each(function(DomCrawler $dom) {
					$data = $this->parse($dom);
					if($data) {
						$data += [
							'customer_postcode' => $this->location->zipcode,
							'customer_location' => $this->location->name,
							'response' => $dom->outerHtml(),
						];
						$this->entry = $this->model::insertOrIgnore($data);
					}
				});
			}
*/
        } catch (Exception $e) {
			$this->log->error($this->location->zipcode . ': ' . $e->getMessage());
        }
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param UriInterface $url
     * @param RequestException $requestException
     * @param UriInterface|null $foundOnUrl
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null,
		?string $linkText = null
    ): void {
        $this->log->error('crawlFailed: ' . $url . ':' . $requestException->getMessage());
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
		if(!$this->entry) {
//			$this->log->info('not found on URL: ' . $this->url);
		}
    }

   /**
     * @return mixed
     */
    public function getEntry()
    {
        return $this->entry;
    }
}

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
		CrawlerLog::create([
			'url'	=> $this->url,
			'content'	=> $content,
		]);
        $crawler = new DomCrawler($content);
        try {
            // a-no-results
			if($crawler->filter('body a-no-results')->count() > 0) {
				$this->log->info($this->location->zipcode . ': no search results');
			} else {
				$this->result = $crawler->filter('body nav.search-results-content');

				if($this->result->count() > 0) {
					$results = $this->result->first()->filter('ol li');
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
                    $file = storage_path('app/public/browsershots/') . $this->postcode.'.jpg';
                    $this->screenshot($content, $url, $file);
				}
			}
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

    protected function screenshot($html, $url, $file)
    {
        $html = mb_convert_encoding($html, 'UTF-8');
        return Browsershot::html($html)
            ->setContentUrl($url)
            ->setExtraHttpHeaders(['Charset' => 'utf-8'])
            ->waitUntilNetworkIdle()
            ->setScreenshotType('jpeg', 70)
            ->userAgent($this->userAgent)
            ->windowSize(812, 375)
            ->deviceScaleFactor(3)
            ->mobile()
            ->touch()
            ->landscape()
            ->save($file)
//            ->screenshot()
        ;
    }

   /**
     * @return mixed
     */
    public function getEntry()
    {
        return $this->entry;
    }
}

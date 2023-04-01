<?php

namespace App\Libs\Crawler\Observer;

use App\Repositories\Traits\ResultParser;
use Exception;
use App\Models\Crawler;
use App\Libs\Crawler\Symfony;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Exception\RequestException;
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
    public function __construct(
        protected string $model,
        protected string $postcode
    ) {}

    /**
     * Called when the crawler will crawl the url.
     *
     * @param UriInterface $url
     */
    public function willCrawl(UriInterface $url): void
    {
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
        ?UriInterface $foundOnUrl = null
    ): void {
        $content = $response->getBody()->getContents();
        $crawler = new DomCrawler($content);
        try {
            // a-no-results
            if($crawler->filter('body a-no-results')->count() > 0) {
                Log::info($this->postcode . ': no search results');
                echo json_encode(['error' => 'no search results for postcode: ' . $this->postcode]);
            } else {
                $this->result = $crawler->filter('body .search-results-content');
                if($this->result->count() > 0) {
                    $this->result->first()->filter('article')->each(function(DomCrawler $article) {
                        $data = $this->parse($article);
                        $data +=  [
                            'customer_postcode' => $this->postcode,
                            'response' => $article->outerHtml(),
                        ];
                        $this->model::updateOrCreate(['customer_postcode' => $this->postcode], $data);
                    });
//                    $file = storage_path('app/public/browsershot/') . $this->postcode.'.jpg';
//                    $this->screenshot($html, $url, $file);
                }
            }
        } catch (Exception $e) {
            Log::info($this->postcode . ': ' . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
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
        ?UriInterface $foundOnUrl = null
    ): void {
        Log::error('crawlFailed: ' . $url . ':' . $requestException->getMessage());
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
        //
    }

    protected function screenshot($html, $url, $file)
    {
        $html = mb_convert_encoding($html, 'UTF-8');
        return Browsershot::html($html)
            ->setContentUrl($url)
            ->setExtraHttpHeaders(['Charset' => 'utf-8'])
            ->waitUntilNetworkIdle()
            ->setScreenshotType('jpeg', 70)
            ->userAgent('Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1')
            ->windowSize(812, 375)
            ->deviceScaleFactor(3)
            ->mobile()
            ->touch()
            ->landscape()
            ->save($file)
//            ->screenshot()
        ;
    }
}

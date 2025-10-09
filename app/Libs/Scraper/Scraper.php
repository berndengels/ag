<?php

namespace App\Libs\Scraper;

use App\Models\CrawlerLog;
use App\Models\Location;
use App\Models\Zipcode;
use App\Models\ZipcodeUnique;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use App\Repositories\Traits\ResultParser;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Scraper
{
	use ResultParser;

	protected static $baseUrl = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen';
	protected $entity;
	protected $image;
	private $usleep = 1000;
	private $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:143.0) Gecko/20100101 Firefox/143.0';
	/**
	 * @var string
	 */
	protected $url;
	/**
	 * @var Eloquent
	 */
	protected $model;

	/**
	 * @param ZipcodeUnique $location
	 */
	public function __construct(
		protected ZipcodeUnique $location
	) {}
	/**
	 * @param ZipcodeUnique $location
	 */
	public function setLocation(ZipcodeUnique $location):self
	{
		$this->location = $location;
		return $this;
	}

	public function run() {
		if(!$this->location) {
			throw new Exception('location object must be set!');
		}
		try {
//        $this->url = str_replace(['%CITY%','%PLZ%'], [urlencode($this->location->name), $this->location->zipcode], $this->url);
			$this->url = trim(str_replace('%PLZ%', $this->location->zipcode, $this->url));
			$browsershot = Browsershot::url($this->url)
				->newHeadless()
				->dismissDialogs()
				->waitForSelector('.dienststellen-result a');

			usleep($this->usleep);

			$content = $browsershot->bodyHtml();
			$crawler = new DomCrawler($content);

			$filter = $crawler->filter('.dienststellen-result a');

			if(!$filter->matches('.dienststellen-result')) {
//				return $this;
			}

			$result = $filter->first();

			$name = $result->filter('h2.title')->first()->text();
			$address = $result->filter('p.visitor-address span');
			$street = $address->eq(0)->text();
			[$postcode, $city] = preg_split('/[\s]+/u', $address->eq(1)->text(), 2, PREG_SPLIT_NO_EMPTY);
			$link = $result->attr('href');

			if($link) {
				$browsershot = Browsershot::url($link)
					->newHeadless()
					->dismissDialogs()
					->disableImages()
					->disableRedirects()
					->disableJavascript()
					->disableCaptureURLS()
					->waitUntilNetworkIdle()
					->waitForSelector('section.ba-contact-infobox')
				;

				$content = $browsershot->bodyHtml();
				$crawler = new DomCrawler($content);
				$article = $crawler->filter('section.ba-contact-infobox article')->first();
				$contectBoxes = $article->filter('div.ba-contact-box');
				$contact = $contectBoxes->eq(1);
				$fon = $contact->filter('a')->each(fn(DomCrawler $item) => $item->attr('href'));
				array_pop($fon);
				array_walk($fon, fn($a) => preg_replace("/^tel\:/i",'', trim($a)));

//				$openingTimes = str_replace('<br>',', ', $contact->filter('p')->eq(1)->html());
				$postAddress = strip_tags(str_replace('<br>',"\n", $contectBoxes->eq(2)->filter('address')->html()));

				$this->entity = [
					'name'		=> $name,
					'fon'		=> implode(";", $fon),
					'street'	=> $street,
					'postcode'	=> $postcode,
					'city'		=> $city,
					'url'		=> $link,
					'post_address'	=> $postAddress,
//					'openingTimes' => $openingTimes,
				];
/*
				$disk = Storage::disk('browsershots');
				$file = $this->location->zipcode . '.png';
				$imagePath = $disk->path($file);

				if($disk->exists($file)) {
					$disk->delete($file);
				}

				$browsershot
					->landscape()
					->save($imagePath);

				$this->image = $disk->url($file);
*/
			}
		} catch (Exception $e) {
			$this->log->error($this->location->zipcode . ': ' . $e->getMessage());
		}

		return $this;
	}

	public function getUrl(): string
	{
		return trim($this->url);
	}

	public function getEntity()
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
}
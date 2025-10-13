<?php

namespace App\Libs\Scraper;

use App\Models\ZipcodeUnique;
use Spatie\Browsershot\Browsershot;
use App\Repositories\Traits\ResultParser;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Scraper
{
	use ResultParser;

	protected static $baseUrl = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen';
	protected $entity;
	protected $error;
	protected $image;
	private $usleep = 1000;
	private $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:143.0) Gecko/20100101 Firefox/143.0';
	private $zipApi = "https://zip-api.eu/api/v2/info/zip?fields=placeName&countryCode=DE&postalCode=";

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

	public function run() {
		if(!$this->location) {
			throw new Exception('location object must be set!');
		}
		try {
			$this->url = trim(str_replace('%PLZ%', $this->location->zipcode, $this->url));
			$browsershot = Browsershot::url($this->url)
				->newHeadless()
				->dismissDialogs()
				->waitForSelector('.dienststellen-result a');

			usleep($this->usleep);

			$content = $browsershot->bodyHtml();
			$crawler = new DomCrawler($content);

			$filter = $crawler->filter('.dienststellen-result a');

			if(0 === $filter->count()) {
				$this->error = $this->location->zipcode;
				return $this;
/*
				$cities = $this->getZipInfo($this->location->zipcode);

				if(isset($cities['error'])) {
					return $this;
				}

				if($cities && $cities->count() > 0) {
					$this->url = $this->url .'&ort=' . urlencode($cities->first());
					$browsershot = Browsershot::url($this->url)
						->newHeadless()
						->dismissDialogs()
						->waitForSelector('.dienststellen-result a');

					usleep($this->usleep);

					$content = $browsershot->bodyHtml();
					$crawler = new DomCrawler($content);

					$filter = $crawler->filter('.dienststellen-result a');
				}
*/
			}

			$result = $filter->filter('a')->first();

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
			$this->error = $this->location->zipcode;
			$this->log->error($this->location->zipcode . ': ' . $e->getMessage());
		}

		return $this;
	}

	private function getZipInfo($zipcode)
	{
		$url = $this->zipApi . $zipcode;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . env('ZIP_API_TOKEN')
		]);

		$response = json_decode(curl_exec($ch));

		if(isset($response->result)) {
			$response = collect($response->result)->map(fn($item) => $item->attributes->placeName);
		}

		if (curl_errno($ch)) {
			$response['error'] = curl_error($ch);
		}

		curl_close($ch);

		return $response;
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
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @return mixed
	 */
	public function getImage()
	{
		return $this->image;
	}
}
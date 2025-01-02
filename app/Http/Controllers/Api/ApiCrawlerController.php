<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Libs\Crawler\Crawler as MyCrawler;

abstract class ApiCrawlerController extends Controller
{
    protected $baseUrl = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen';
    protected $relation;
    protected $url;
    /**
     * @var MyCrawler
     */
    protected $crawler;
    protected $locationModel = Location::class;
    protected $foundField;

    /**
     * Display a listing of the resource.
     */
    public function locations()
    {
        $locations = $this->locationModel::select(['id','zipcode','name'])
			->where($this->foundField, '=', 0)
            ->groupBy('zipcode')
            ->get(['id','zipcode','name'])
        ;
		$locations = LocationResource::collection($locations);

        return response()->json(['locations' => $locations]);
    }

    /**
     * Display a listing of the resource.
     */
    public function count()
    {
        $count = $this->locationModel::where($this->foundField, '=', 1)
			->groupBy('zipcode')
            ->count()
        ;
        return response()->json(['count' => $count]);
    }

    public function crawle($postcode, $city = null)
    {
		$query = $this->locationModel::select()
			->whereZipcode($postcode);
/*
		if($city) {
			$query->whereName($city);
		}
*/
        $location = $query->first();

		if(!$location) {
			$response = [
				'error' => 'no location found',
				'entity' => null,
				'url'   => null,
			];

			return response()->json($response);
		}

        /**
         * @var MyCrawler $crawler
         */
        $crawler = new $this->crawler();
        $run = $crawler
            ->setLocation($location)
            ->run()
        ;

//        $imageURL   = '/storage/browsershot/'.$postcode.'.jpg';
//        $imagePath  = public_path('storage/browsershot/') . $postcode.'.jpg';

        $response = [
            'error' => $run->getEntity() ? false : true,
            'entity' => $run->getEntity(),
            'url'   => $run->getUrl(),
//            'image' => file_exists($imagePath) ? $imageURL : null,
        ];
        return response()->json($response);
    }

    public function setFounded(Location $location)
    {
        $location->update([$this->foundField => 1]);
        $location->refresh();

        return response()->json($location);
    }
}

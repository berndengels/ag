<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $locations = $this->locationModel::where($this->foundField, '=', 0)
            ->groupBy('zipcode','name')
            ->get()
        ;
        return response()->json(['locations' => $locations]);
    }

    /**
     * Display a listing of the resource.
     */
    public function count()
    {
        $locations = $this->locationModel::where($this->foundField, '=', 1)
            ->groupBy('zipcode','name')
            ->get()
        ;
        return response()->json(['count' => $locations->count()]);
    }

    public function crawle($postcode, $city)
    {
        $location = $this->locationModel::whereZipcode($postcode)
            ->whereName($city)
            ->first()
        ;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

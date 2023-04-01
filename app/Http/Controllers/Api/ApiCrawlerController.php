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

    /**
     * Display a listing of the resource.
     */
    public function locations()
    {
        $locations = $this->locationModel::doesntHave($this->relation)
            ->groupBy('zipcode')
            ->get()
        ;
        return response()->json(['locations' => $locations]);
    }

    /**
     * Display a listing of the resource.
     */
    public function count()
    {
        $locations = $this->locationModel::has($this->relation)
            ->groupBy('zipcode')
            ->get()
        ;
        return response()->json(['count' => $locations->count()]);
    }

    public function crawle($postcode)
    {
        $location = $this->locationModel::whereZipcode($postcode)->first();
        /**
         * @var MyCrawler $crawler
         */
        $crawler = new $this->crawler();
        $entity = $crawler
            ->setLocation($location)
            ->run()
            ->getEntity()
        ;
        $imageURL   = '/storage/browsershot/'.$postcode.'.jpg';
        $imagePath  = public_path('storage/browsershot/') . $postcode.'.jpg';

        $response = [
            'error' => $entity ? false : true,
            'entity' => $entity,
            'image' => file_exists($imagePath) ? $imageURL : null,
        ];
        return response()->json($response);
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
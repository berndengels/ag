<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Zipcode;
use Illuminate\Http\Request;
use App\Models\Arbeitsagentur;
use App\Http\Requests\StoreArbeitsamtRequest;
use App\Http\Requests\UpdateArbeitsagenturRequest;
use Laravel\Dusk\Chrome\SupportsChrome;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class ArbeitsagenturController extends Controller
{
    use SupportsChrome;

    private $locations;
    private $baseUrl = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen';
    private $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=arbeitsagenturen&volltext=%CITY%&plz=%PLZ%';

    public function __construct()
    {
        $this->locations = Zipcode::all(['zipcode','name'])
            ->keyBy('zipcode')
            ->map(fn($item) => $item->zipcode . ' ' .$item->name);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Arbeitsagentur::paginate($this->paginatorLimit);
        return view('pages.arbeitsagenturen.index', [
            'data' => $data,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function repair()
    {
        $data = Arbeitsagentur::whereUrl(null)->get()->each(function (Arbeitsagentur $item) {
            $url = (new DomCrawler($item->response))->filter('article h3 a')->attr('href');
            dd($url);
        });

//        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Arbeitsagentur $arbeitsagentur)
    {
        $html = Arbeitsagentur::whereFon(null)->first()->response;
        $crawler = new DomCrawler($html);
        $data = $crawler->filter('article')->each(fn(DomCrawler $item) => $item->children());
        return view('pages.arbeitsagenturen.show', [
            'arbeitsagentur' => $arbeitsagentur,
            'data' => $data,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function crawle()
    {
        return view('pages.arbeitsagenturen.crawler');
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $postcode = $request->post('postcode') ?? null;
        if($postcode) {
            $arbeitsagentur = Arbeitsagentur::whereCustomerPostcode($postcode)->first();
        }
        return view('pages.arbeitsagenturen.search', [
            'arbeitsagentur' => $arbeitsagentur,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.jobcenters.create', [
            'locations' => $this->locations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArbeitsamtRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Arbeitsagentur $arbeitsagentur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArbeitsagenturRequest $request, Arbeitsagentur $arbeitsagentur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Arbeitsagentur $arbeitsagentur)
    {
        //
    }
}

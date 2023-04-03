<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobcenterRequest;
use App\Http\Requests\UpdateJobcenterRequest;
use App\Models\Jobcenter;
use App\Models\Location;
use App\Models\ZipCoordinate;
use App\Repositories\JobcenterRepository;
use App\Repositories\LocationRepository;
use Laravel\Dusk\Chrome\SupportsChrome;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class JobcenterController extends Controller
{
    use SupportsChrome;

    private $locations;
    private $baseUrl = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen';
    private $url = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen?in=jobcenter&volltext=%CITY%&plz=%PLZ%';

    public function __construct()
    {
//        LocationRepository::repair();
        $this->locations = Location::all(['zipcode','name'])
            ->keyBy('zipcode')
            ->map(fn($item) => $item->zipcode . ' ' .$item->name);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Jobcenter::paginate($this->paginatorLimit);
        return view('pages.jobcenters.index', [
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jobcenter $jobcenter)
    {
        $html = Jobcenter::whereFon(null)->first()->response;
        $crawler = new DomCrawler($html);
        $data = $crawler->filter('article')->each(fn(DomCrawler $item) => $item->children());
        return view('pages.jobcenters.show', [
            'jobcenter' => $jobcenter,
            'data' => $data,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function crawle()
    {
        return view('pages.jobcenters.crawler');
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $postcode = $request->post('postcode') ?? null;
        if($postcode) {
            $jobcenter = Jobcenter::whereCustomerPostcode($postcode)->first();
        }
        return view('pages.jobcenters.search', [
            'jobcenter' => $jobcenter,
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
    public function store(StoreJobcenterRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jobcenter $jobcenter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobcenterRequest $request, Jobcenter $jobcenter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jobcenter $jobcenter)
    {
        //
    }
}

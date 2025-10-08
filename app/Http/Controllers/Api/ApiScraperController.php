<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomerAgency;
use App\Models\EmploymentAgency;
use App\Models\JobCentre;
use App\Models\Location;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Libs\Scraper\Scraper;
use App\Models\Zipcode;

abstract class ApiScraperController extends Controller
{
    protected $baseUrl = 'https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen';
	/**
	 * @var EmploymentAgency|JobCentre
	 */
    protected $model;
    protected $url;
    /**
     * @var Scraper
     */
    protected $scraper;
    protected $locationModel = Zipcode::class;
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

    public function scrape($postcode)
    {
		$location = $this->locationModel::select()
			->whereZipcode($postcode)
			->first()
		;

		if(!$location) {
			$response = [
				'error' => 'no location found',
				'entity' => null,
				'url'   => null,
				'image' => null,
			];

			return response()->json($response);
		}

        /**
         * @var Scraper $scraper
         */
        $scraper = new $this->scraper();
        $run = $scraper
            ->setLocation($location)
            ->run()
        ;

		$entity = $run->getEntity();

		if($entity) {

			$id = $this->model::insertOrIgnore($entity);

			if($id && $id > 0) {
				$model = $this->model::find($id);

			} else {
				$model = $this->model::select()
					->whereName($entity['name'])
					->wherePostcode($entity['postcode'])
					->whereCity($entity['city'])
					->whereStreet($entity['street'])
					->first()
				;
			}

			CustomerAgency::insertOrIgnore([
				'agency_type'	=> $this->model,
				'agency_id'	=> $model->id,
				'postcode'	=> $postcode,
			]);
		}

        $response = [
            'error' => $run->getEntity() ? false : true,
            'entity' => $entity,
            'url'   => $run->getUrl(),
			'image' => $run->getImage(),
        ];

        return response()->json($response);
    }

    public function setFounded(Zipcode $zipcode)
    {
		$zipcode->update([$this->foundField => 1]);
		$zipcode->refresh();

        return response()->json($zipcode);
    }
}

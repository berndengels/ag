<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomerAgency;
use App\Models\EmploymentAgency;
use App\Models\JobCentre;
use App\Http\Controllers\Controller;
use App\Http\Resources\ZipcodeResource;
use App\Libs\Scraper\Scraper;
use App\Models\Zipcode;
use App\Models\ZipcodeUnique;

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
    protected $locationModel = ZipcodeUnique::class;
    protected $foundField;

    /**
     * Display a listing of the resource.
     */
    public function locations()
    {
        $locations = $this->locationModel::select(['id','zipcode'])
			->where($this->foundField, '=', 0)
			->whereError(false)
			->get();

		$locations = ZipcodeResource::collection($locations);

        return response()->json(['locations' => $locations]);
    }

    /**
     * Display a listing of the resource.
     */
    public function count()
    {
        $count = $this->locationModel::select()
			->where($this->foundField, '=', 1)
			->whereError(false)
			->count();

        return response()->json(['count' => $count]);
    }

    public function scrape($postcode)
    {
		$location = $this->locationModel::select('zipcode')
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
        $scraper = new $this->scraper($location);
        $run = $scraper->run();

		$entity = $run->getEntity();
		$error = $run->getError();
		$id = null;

		if($error) {
			$location->update(['error' => 1]);
		} elseif($entity) {

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

			$location->update([$this->foundField => 1]);
		}

        $response = [
            'error' => $run->getError() ? false : true,
            'entity' => $entity,
            'url'   => $run->getUrl(),
			'image' => $run->getImage(),
			'added'	=> $id > 0 ? true : false,
        ];

        return response()->json($response);
    }

    public function setFounded(ZipcodeUnique $zipcodeUnique)
    {
		$zipcodeUnique->update([$this->foundField => 1]);
		$zipcodeUnique = new ZipcodeResource($zipcodeUnique);

        return response()->json($zipcodeUnique);
    }
}

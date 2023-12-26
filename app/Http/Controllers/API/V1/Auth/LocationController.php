<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\Profile\BaseController;
use App\Http\Resources\API\Location\CountryResource;
use App\Http\Resources\API\Location\StateResource;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;

class LocationController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function getCountries()
    {
        $countries = Country::query()->get();

        return $this->respondData(CountryResource::collection($countries));
    }

    /**
     * @param $countryId
     * @return JsonResponse
     */
    public function getStates($countryId)
    {
        $states = State::query()->where('country_id', $countryId)->get();

        return $this->respondData(StateResource::collection($states));
    }
}

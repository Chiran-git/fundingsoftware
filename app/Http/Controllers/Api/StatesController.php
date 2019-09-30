<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatesController extends Controller
{
    /**
     * Method to get a list of all states for the given country
     *
     * @param string $countryCode Two char country code
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Response\JsonResponse
     */
    public function index($countryCode, Request $request)
    {
        $countryCode = strtoupper($countryCode);

        if (config('states.' . $countryCode)) {
            $states = config('states.' . $countryCode);
        } else {
            $states = config('states.' . config('app.defaults.country'));
        }

        return response()->json($states);
    }
}

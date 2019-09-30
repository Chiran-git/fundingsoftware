<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Repositories\Contracts\CurrencyRepositoryInterface;

class CurrenciesController extends Controller
{
    /**
     * Method to get all currencies
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Repositories\Contracts\CurrencyRepositoryInterface $repo
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, CurrencyRepositoryInterface $repo)
    {
        return CurrencyResource::collection($repo->all());
    }
}

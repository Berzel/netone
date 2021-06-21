<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\RechargePinlessRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RechargePinlessController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RechargePinlessRequest $request) : JsonResponse
    {
        return response()->json([], HttpResponse::HTTP_CREATED);
    }
}

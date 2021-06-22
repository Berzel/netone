<?php

namespace App\Http\Controllers;

use App\Commands\RechargeCommand;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RechargePinlessRequest;
use App\Services\RechargeService;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RechargePinlessController extends Controller
{

    /**
     * The recharge service instance
     *
     * @var RechargeService
     */
    private RechargeService $rechargeService;

    /**
     * Create a new controller instance
     *
     * @param RechargeService $rechargeService
     * @return void
     */
    public function __construct(RechargeService $rechargeService)
    {
        $this->rechargeService = $rechargeService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RechargePinlessRequest $request) : JsonResponse
    {
        $topup = $this->rechargeService->recharge(new RechargeCommand($request->all()));
        return response()->json($topup, HttpResponse::HTTP_CREATED);
    }
}

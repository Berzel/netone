<?php

namespace App\Http\Controllers;

use App\Commands\CheckEcocashPaymentCommand;
use App\Http\Requests\EcocashWebhookRequest;
use App\Models\EcocashPayment;
use App\Services\EcocashService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class EcocashWebhookController extends Controller
{
    /**
     * The ecocash service instance
     *
     * @var EcocashService
     */
    private EcocashService $ecocashService;

    /**
     * Create a new controller instance
     *
     * @param
     */
    public function __construct(EcocashService $ecocashService)
    {
        $this->ecocashService = $ecocashService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(EcocashWebhookRequest $request, EcocashPayment $payment) : JsonResponse
    {
        $this->ecocashService->checkPaymentStatus(new CheckEcocashPaymentCommand($payment->toArray()));
        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}

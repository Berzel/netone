<?php

namespace App\Http\Controllers;

use App\Services\NetoneAirtimeService;
use Illuminate\Http\Request;

class CheckAgentBalance extends Controller
{
    /**
     * NetoneAirtimeService instance
     *
     * @var NetoneAirtimeService
     */
    private NetoneAirtimeService $netoneAirtimeService;

    /**
     * Create a new controller instance
     *
     * @param NetoneAirtimeService $netoneAirtimeService
     * @return void
     */
    public function __construct(NetoneAirtimeService $netoneAirtimeService)
    {
        $this->netoneAirtimeService = $netoneAirtimeService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $balance = $this->netoneAirtimeService->checkAgentBalance();
        return response()->json($balance);
    }
}

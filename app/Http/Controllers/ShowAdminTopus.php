<?php

namespace App\Http\Controllers;

use App\Models\Topup;
use Illuminate\Http\Request;

class ShowAdminTopus extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->json(Topup::with(['payment'])->latest()->paginate(10)->onEachSide(1));
    }
}

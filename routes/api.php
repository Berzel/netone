<?php

use App\Http\Controllers\EcocashWebhookController;
use App\Http\Controllers\RechargePinlessController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('topups', RechargePinlessController::class)->name('airtime.topup');
    Route::post('ecocash-payments/{payment}/webhook', EcocashWebhookController::class)->name('ecocash.webhook');
});

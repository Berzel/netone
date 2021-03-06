<?php

use App\Http\Controllers\CheckAgentBalance;
use App\Http\Controllers\EcocashWebhookController;
use App\Http\Controllers\RechargePinlessController;
use App\Http\Controllers\ShowAdminTopus;
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

    // Magetsi Admin endpoints
    Route::prefix('admin')->group(function (){
        Route::get('balance', CheckAgentBalance::class)->name('balance.check');
        Route::get('topups', ShowAdminTopus::class)->name('admin.topups.index');
    });

    // Core functionality
    Route::post('topups', RechargePinlessController::class)->name('airtime.topup');
    Route::any('ecocash-payments/{payment}/webhook', EcocashWebhookController::class)->name('ecocash.webhook');
});

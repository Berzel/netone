<?php

namespace App\Listeners;

use App\Events\TopupFailed;
use App\Jobs\RetryTopup;

class InitiateRetryTopup
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TopupFailed $event)
    {
        RetryTopup::dispatch($event->topup)->delay(now()->addSeconds(mt_rand($twoMin = 2*60, $fiveMin = 5*60)));
    }
}

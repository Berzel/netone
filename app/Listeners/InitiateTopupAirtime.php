<?php

namespace App\Listeners;

use App\Events\EcocashPaymentCompleted;
use App\Jobs\TopupAirtime;

class InitiateTopupAirtime
{
    /**
     * Handle the event.
     *
     * @param  EcocashPaymentCompleted  $event
     * @return void
     */
    public function handle(EcocashPaymentCompleted $event)
    {
        TopupAirtime::dispatch($event->ecocashPayment->topup);
    }
}

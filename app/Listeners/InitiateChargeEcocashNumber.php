<?php

namespace App\Listeners;

use App\Events\EcocashPaymentCreated;
use App\Jobs\ChargeEcocashNumber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InitiateChargeEcocashNumber
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EcocashPaymentCreated $event) : void
    {
        ChargeEcocashNumber::dispatch($event->ecocashPayment);
    }
}

<?php

namespace App\Jobs;

use App\Commands\ChargeEcocashNumberCommand;
use App\Models\EcocashPayment;
use App\Models\Topup;
use App\Services\EcocashService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChargeEcocashNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The EcocashPayment instance
     *
     * @var EcocashPayment
     */
    private EcocashPayment $ecocashPayment;

    /**
     * Create a new job instance.
     *
     * @param Topup $topup
     * @return void
     */
    public function __construct(EcocashPayment $ecocashPayment)
    {
        $this->ecocashPayment = $ecocashPayment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EcocashService $ecocashService) : void
    {
        $ecocashService->charge(new ChargeEcocashNumberCommand([
            'id' => $this->ecocashPayment->id
        ]));
    }
}

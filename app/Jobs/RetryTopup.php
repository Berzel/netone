<?php

namespace App\Jobs;

use App\Commands\TopupAirtimeCommand;
use App\Models\Topup;
use App\Services\NetoneAirtimeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RetryTopup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Topup instance
     *
     * @var Topup
     */
    private Topup $topup;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Topup $topup)
    {
        $this->topup = $topup;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(NetoneAirtimeService $netoneAirtimeService)
    {
        $netoneAirtimeService->rechargePinless(new TopupAirtimeCommand(['id' => $this->topup->id]));
    }
}

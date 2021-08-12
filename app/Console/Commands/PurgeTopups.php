<?php

namespace App\Console\Commands;

use App\Models\EcocashPayment;
use Illuminate\Console\Command;

class PurgeTopups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topups:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all topups with failed payments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payments = EcocashPayment::whereStatus('failed')->get();

        foreach ($payments as $payment) {
            $payment->topup->delete();
            $payment->delete();
        };

        return 0;
    }
}

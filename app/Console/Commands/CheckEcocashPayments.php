<?php

namespace App\Console\Commands;

use App\Models\EcocashPayment;
use Http;
use Illuminate\Console\Command;

class CheckEcocashPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecocash-payments:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for ecocash payments status.';

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
        $payments = EcocashPayment::whereStatus('pending')->oldest()->get();

        foreach ($payments as $payment) {
            Http::get(route('ecocash.webhook', [$payment]));
        };

        return 0;
    }
}

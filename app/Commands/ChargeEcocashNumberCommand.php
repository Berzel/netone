<?php

namespace App\Commands;

use App\Models\Topup;

class ChargeEcocashNumberCommand extends Command {

    /**
     * Create a new command instance
     *
     * @param Topup $topup
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}

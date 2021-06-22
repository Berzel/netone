<?php

namespace App\Commands;

class CreateEcoCashPaymentCommand extends Command {

    /**
     * Create command instance
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}

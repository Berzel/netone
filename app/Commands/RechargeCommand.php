<?php

namespace App\Commands;

class RechargeCommand extends Command {

    /**
     * Create a new command instance
     *
     * @param array $data
     * @return false
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}

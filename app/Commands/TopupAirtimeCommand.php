<?php

namespace App\Commands;

class TopupAirtimeCommand extends Command {

    /**
     * Create a new command instance
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}

<?php

namespace App\Commands;

class Command {

    /**
     * The command data
     *
     * @var array
     */
    protected array $data;

    /**
     * Get the command data
     *
     * @return array
     */
    public function data() : array
    {
        return $this->data;
    }

    /**
     * Get an item from data
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data()[$key];
    }
}

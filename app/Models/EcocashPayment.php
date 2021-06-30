<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EcocashPayment extends Payment
{
    use HasFactory;

    /**
     * The attributes that should be mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'topup_id',
        'ecocash_number'
    ];

    /**
     * Get the client correlator for this payment
     *
     * @return string
     */
    public function getClientCorrelatorAttribute() : string
    {
        return '';
    }

    /**
     * Get the reference code for this payment
     *
     * @return string
     */
    public function getReferenceCodeAttribute() : string
    {
        return '';
    }

    /**
     * Get the poll url for this payment
     *
     * @return string
     */
    public function getPollUrlAttribute() : string
    {
        return '';
    }

    /**
     * The endpoint on this server that ecocash will be
     * posting updates to
     *
     * @return string
     */
    public function getListenerEndpointAttribute() : string
    {
        return '';
    }
}

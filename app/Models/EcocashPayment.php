<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * The owning Topup model
     *
     * @return
     */
    public function topup() : BelongsTo
    {
        return $this->belongsTo(Topup::class);
    }

    /**
     * Get the reference code for this payment
     *
     * @return string
     */
    public function getReferenceCodeAttribute() : string
    {
        return 'QWIK_NET_'.$this->id;
    }

    /**
     * Get the client correlator for this payment
     *
     * @return string
     */
    public function getClientCorrelatorAttribute() : string
    {
        return $this->reference_code.'_TAMP_'.$this->created_at->timestamp;
    }

    /**
     * Get the poll url for this payment
     *
     * @return string
     */
    public function getPollUrlAttribute() : string
    {
        return config('services.ecocash.merchant.api.url') .'/'. $this->ecocash_number.'/transactions/amount/'.$this->client_correlator;
    }

    /**
     * The endpoint on this server that ecocash will be
     * posting updates to
     *
     * @return string
     */
    public function getListenerEndpointAttribute() : string
    {
        return route('ecocash.webhook', ['payment' => $this->id]);
    }

    /**
     * Check if this payment is paid
     *
     * @return bool
     */
    public function isPaid() : bool
    {
        return $this->status === 'paid';
    }
}

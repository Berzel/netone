<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topup extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that should be mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'status',
        'netone_number',
        'payment_id',
        'payment_method',
    ];

    /**
     * This topup's payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payment()
    {
        return $this->morphTo(__FUNCTION__, 'payment_method', 'payment_id');
    }

    /**
     * Get the reference code
     *
     * @return string
     */
    public function getReferenceCodeAttribute() : string
    {
        return 'QWIK_NET_T'.$this->id.'_TAMP_'.$this->created_at->timestamp.'_ATT_'.$this->attempts;
    }

    /**
     * Check whether the top is commplete
     *
     * @return bool
     */
    public function getIsCompleteAttribute() : bool
    {
        return $this->status === 'completed';
    }
}

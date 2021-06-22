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
}

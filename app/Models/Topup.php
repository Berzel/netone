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
}

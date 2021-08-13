<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attrobutes that should not be mass assignable
     *
     * @var array
     */
    protected $guarded = [];
}

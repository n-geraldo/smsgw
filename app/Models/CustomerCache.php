<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCache extends Model
{
    protected $table = 'customers_cache';

    protected $fillable = [
        'username',
        'phone',
        'expiration_date',
        'last_seen_at',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'last_seen_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'sent_at' => 'date:Y-m-d',
        'arrived_at' => 'data:Y-m-d'
    ];

    protected $fillable = [
        'order_id', 'status_id', 'name', 'phone', 'address',
        'remark', 'sent_at', 'arrived_at'
    ];
}

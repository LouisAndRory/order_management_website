<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cookie extends Model
{
    protected $table = 'cookies';

    public $timestamps = true;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    protected $fillable = [
        'name', 'slug','enabled'
    ];
}

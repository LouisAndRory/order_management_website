<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    protected $table = 'packs';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    protected $fillable = [
        'name', 'slug', 'enabled'
    ];
}

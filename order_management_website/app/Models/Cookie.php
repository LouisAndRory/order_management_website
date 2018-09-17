<?php

namespace App\Models;

use App\Models\Scopes\EnabledScope;
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
    ];

    protected $fillable = [
        'name', 'slug','enabled'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EnabledScope());
    }
}

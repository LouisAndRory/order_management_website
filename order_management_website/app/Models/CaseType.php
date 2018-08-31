<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseType extends Model
{
    protected $table = 'case_types';

    public $timestamps = true;

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

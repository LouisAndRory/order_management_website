<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageStatus extends Model
{
    protected $table = 'package_statuses';

    public $timestamps = false;

    protected $dates = [];

    protected $casts = [];

    protected $fillable = [
        'name'
    ];
}

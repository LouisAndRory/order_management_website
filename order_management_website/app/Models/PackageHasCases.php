<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageHasCases extends Model
{
    protected $table = 'package_has_cases';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'package_id', 'case_id', 'amount'
    ];

    public function package()
    {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }

    public function case()
    {
        return $this->belongsTo('App\Models\CaseModel', 'case_id');
    }
}

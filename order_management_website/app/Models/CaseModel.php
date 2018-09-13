<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    protected $table = 'cases';

    public $timestamps = true;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [];

    protected $fillable = [
        'order_id', 'case_type_id', 'price', 'amount'
    ];

    public function cookies()
    {
        return $this->hasMany('App\Models\CaseHasCookies', 'case_id');
    }
}

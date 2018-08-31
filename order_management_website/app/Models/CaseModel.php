<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    protected $table = 'cases';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [];

    protected $fillable = [
        'order_id', 'case_type_od', 'price', 'amount'
    ];
}

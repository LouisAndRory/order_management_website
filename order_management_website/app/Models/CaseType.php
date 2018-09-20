<?php

namespace App\Models;

use DB;
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
    ];

    protected $fillable = [
        'name', 'slug','enabled'
    ];

    protected $appends = [
        'deletable'
    ];

    public function getDeletableAttribute()
    {
        return !CaseModel::where('case_type_id', '=', $this->id)
            ->exists();
    }
}

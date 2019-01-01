<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CaseModel
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CaseHasCookies[] $cookies
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CaseModel query()
 * @mixin \Eloquent
 */
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

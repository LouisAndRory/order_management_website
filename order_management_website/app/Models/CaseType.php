<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CaseType
 *
 * @property-read mixed $deletable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CaseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CaseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CaseType query()
 * @mixin \Eloquent
 */
class CaseType extends Model
{
    protected $table = 'case_types';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'img_urls' => 'array'
    ];

    protected $fillable = [
        'name', 'slug','enabled', 'img_urls'
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

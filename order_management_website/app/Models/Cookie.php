<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cookie
 *
 * @property-read mixed $deletable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cookie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cookie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cookie query()
 * @mixin \Eloquent
 */
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

    protected $appends = [
        'deletable'
    ];

    public function getDeletableAttribute()
    {
        return !DB::table('case_has_cookies')
            ->where('cookie_id', '=', $this->id)
            ->exists();
    }
}

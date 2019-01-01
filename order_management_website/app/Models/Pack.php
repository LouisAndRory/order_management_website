<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pack
 *
 * @property-read mixed $deletable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pack query()
 * @mixin \Eloquent
 */
class Pack extends Model
{
    protected $table = 'packs';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
    ];

    protected $fillable = [
        'name', 'slug', 'enabled'
    ];

    protected $appends = [
        'deletable'
    ];

    public function getDeletableAttribute()
    {
        return !DB::table('case_has_cookies')
            ->where('pack_id', '=', $this->id)
            ->exists();
    }
}

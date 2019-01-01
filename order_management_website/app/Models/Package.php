<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Package
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PackageHasCases[] $cases
 * @property-read mixed $shipped
 * @property-write mixed $arrived_at
 * @property-write mixed $sent_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Package onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Package withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Package withoutTrashed()
 * @mixin \Eloquent
 */
class Package extends Model
{
    use SoftDeletes;

    protected $table = 'packages';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'shipped'
    ];

    protected $casts = [
        'sent_at' => 'date:Y-m-d',
        'arrived_at' => 'date:Y-m-d'
    ];

    protected $fillable = [
        'order_id', 'status_id', 'name', 'phone', 'address',
        'remark', 'sent_at', 'arrived_at', 'checked'
    ];

    public function setSentAtAttribute($value)
    {
        if ($value) {
            $this->attributes['sent_at'] = Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateString();
        } else {
            $this->attributes['sent_at'] = null;
        }
    }

    public function setArrivedAtAttribute($value)
    {
        if ($value) {
            $this->attributes['arrived_at'] = Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateString();
        } else {
            $this->attributes['arrived_at'] = null;
        }
    }

    public function getShippedAttribute()
    {
        return array_get($this->attributes, 'sent_at', null) !== null;
    }

    public function cases()
    {
        return $this->hasMany('App\Models\PackageHasCases', 'package_id');
    }
}

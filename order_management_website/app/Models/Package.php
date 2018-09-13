<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'arrived_at' => 'data:Y-m-d'
    ];

    protected $fillable = [
        'order_id', 'status_id', 'name', 'phone', 'address',
        'remark', 'sent_at', 'arrived_at'
    ];

    public function setSentAtAttribute($value)
    {
        if ($value) {
            $this->attributes['sent_at'] = Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateString();
        }
    }

    public function setArrivedAtAttribute($value)
    {
        if ($value) {
            $this->attributes['arrived_at'] = Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateString();
        }
    }

    public function getShippedAttribute()
    {
        return in_array('sent_at', $this->attributes) && $this->attributes['sent_at'] !== null;
    }

    public function cases()
    {
        return $this->hasMany('App\Models\PackageHasCases', 'package_id');
    }
}

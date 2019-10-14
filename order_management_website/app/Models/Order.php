<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Order
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CaseModel[] $cases
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Package[] $packages
 * @property-write mixed $engaged_date
 * @property-write mixed $married_date
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order withoutTrashed()
 * @mixin \Eloquent
 */
class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'engaged_date' => 'date:Y-m-d',
        'married_date' => 'date:Y-m-d',
        'img_urls' => 'array'
    ];

    protected $fillable = [
        'status_id', 'name', 'name_backup', 'phone', 'phone_backup',
        'email', 'deposit', 'extra_fee', 'engaged_date', 'married_date',
        'remark', 'card_required', 'wood_required', 'final_paid', 'img_urls'
    ];

    public function setEngagedDateAttribute($value)
    {
        if ($value) {
            $this->attributes['engaged_date'] = Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateString();
        } else {
            $this->attributes['engaged_date'] = null;
        }
    }

    public function setMarriedDateAttribute($value)
    {
        if ($value) {
            $this->attributes['married_date'] = Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateString();
        } else {
            $this->attributes['married_date'] = null;
        }
    }

    public function cases()
    {
        return $this->hasMany('App\Models\CaseModel', 'order_id');
    }

    public function packages()
    {
        return $this->hasMany('App\Models\Package', 'order_id');
    }
}

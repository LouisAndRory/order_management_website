<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'married_date' => 'data:Y-m-d'
    ];

    protected $fillable = [
        'status_id', 'name', 'name_backup', 'phone', 'phone_backup',
        'email', 'deposit', 'extra_fee', 'engaged_date', 'married_date',
        'remark', 'card_required', 'wood_required', 'final_paid'
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
}

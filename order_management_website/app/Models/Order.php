<?php

namespace App\Models;

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
        'card_required' => 'boolean',
        'wood_required' => 'boolean',
        'final_paid' => 'boolean',
        'engaged_date' => 'date:Y-m-d',
        'married_date' => 'data:Y-m-d'
    ];

    protected $fillable = [
        'status_id', 'name', 'name_backup', 'phone', 'phone_backup',
        'email', 'deposit', 'extra_fee', 'engaged_date', 'married_date',
        'remark', 'card_required', 'wood_required', 'final_paid'
    ];

    public function cases()
    {
        return $this->hasMany('App\Models\CaseModel', 'order_id');
    }
}

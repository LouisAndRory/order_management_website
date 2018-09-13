<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseHasCookies extends Model
{
    protected $table = 'case_has_cookies';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'case_id', 'cookie_id', 'pack_id', 'amount'
    ];

    public function cookie()
    {
        return $this->belongsTo('App\Models\Cookie', 'cookie_id');
    }

    public function pack()
    {
        return $this->belongsTo('App\Models\Pack', 'pack_id');
    }
}

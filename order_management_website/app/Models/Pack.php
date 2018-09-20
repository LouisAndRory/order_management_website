<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

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

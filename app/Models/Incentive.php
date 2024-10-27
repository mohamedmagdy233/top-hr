<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    protected $fillable = [
        'user_id',
        'incentive',
        'value',
        'date',
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

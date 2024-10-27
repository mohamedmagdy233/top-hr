<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceDiscount extends Model
{
    use HasFactory;

    protected $table ='advance_discount';

    protected $fillable = [
        'value',
        'date',
        'user_id',
        'note',


    ];


    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

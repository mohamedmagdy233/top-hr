<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayType extends Model
{
    use HasFactory;
//test
    protected $table = 'holiday_types';

    protected $guarded = [];
}

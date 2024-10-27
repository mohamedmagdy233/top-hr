<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'lat',
        'long',
        'image',
        'date',
        'diff_time',
        'check_out_lat',
        'check_out_long',
        'check_out_image',
        'force_checkout',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function workHour()
    {

        $hours = intdiv($this->diff_time, 60);
        $minutes = $this->diff_time % 60;
        $this->diff_time = sprintf('%02d:%02d', $hours, $minutes);
        return $this->diff_time;
    }


    public function diffCheckOutFromCheckIn()
    {
        $check_in = Carbon::parse($this->check_in);
        $check_out = Carbon::parse($this->check_out);

        $diff_in_minutes = $check_in->diffInMinutes($check_out);

        $hours = floor($diff_in_minutes / 60);
        $minutes = $diff_in_minutes % 60;

        // Format the result as HH:MM
        $this->diff_time = sprintf('%02d:%02d', $hours, $minutes);

        return $this->diff_time;
    }

}

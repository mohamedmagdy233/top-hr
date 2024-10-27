<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticated;

class User extends Authenticated implements JWTSubject
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
    protected $fillable = [
        'branch_id',
        'group_id',
        'name',
        'code',
        'phone',
        'password',
        'fcm_token',
        'status',
        'salary',
        'holidays',
        'registered_at',
        'image'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }


    public function oprations()
    {
        return $this->hasMany(Incentive::class, 'user_id', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    public function attendances()
    {

        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }

    public function advances()
    {
        return $this->hasMany(Advance::class, 'user_id', 'id');
    }

    public function incentives()
    {

        return $this->hasMany(Incentive::class, 'user_id', 'id');

    }

    public function holidays()
    {

        return $this->hasMany(Holiday::class, 'user_id', 'id');

    }
}

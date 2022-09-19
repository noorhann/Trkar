<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\HasPermissionsTrait;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasPermissionsTrait;

    protected $fillable = [
        'uuid',
        'email',
        'password',
        'username',
        'phone',
        'image',
        'country_id',
        'city_id',
        'area_id',
        'address',
        'longitude',
        'latitude',
        'last_login',
        'in_block',
        'vendor',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' =>  'datetime',
    ];

    // Activity Log
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        if (Auth::guard('web')->check()) {
            $activity->causer_type = 'Admin';
        } elseif (Auth::guard('api')->check()) {
            $activity->causer_id = auth('api')->user()->id;
            $activity->causer_type = 'User';
        } elseif (Auth::guard('vendor')->check()) {
            $activity->causer_id = auth('vendor')->user()->id;
            $activity->causer_type = 'Vendor';
        }
    }
    protected static $logAttributes = [
        'id',
        'uuid',
        'email',
        'password',
        'username',
        'phone',
        'image',
        'country_id',
        'city_id',
        'area_id',
        'address',
        'longitude',
        'latitude',
        'last_login',
        'in_block'
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    public function userAddresses()
    {
        return $this->belongsTo(UserAddress::class, 'user_id');
    }
}

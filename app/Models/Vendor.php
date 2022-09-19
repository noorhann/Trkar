<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class Vendor extends Authenticatable implements JWTSubject
{
    use  HasFactory, SoftDeletes, LogsActivity;
    protected $guard = 'vendor';
    protected $fillable = [
        'vendor_uuids_id',
        'email',
        'store',
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
        'bank_account',
        'commercial_number',
        'tax_card_number',
        'notes',
        'approved',
        'last_login',
        'in_block',
        'activation_code',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' =>  'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

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
        'store',
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
        'bank_account',
        'commercial_number',
        'tax_card_number',
        'notes',
        'approved',
        'last_login',
        'in_block'
    ];
    public function setStoreAttribute($value)
    {
        $this->attributes['store'] = $value ?? 'false';
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
    public function vendorStaff()
    {
        return $this->hasMany(VendorStaff::class, 'vendor_id');
    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'user_id');
    }
    public function store()
    {
        return $this->hasOne(Store::class, 'vendor_id');
    }
}

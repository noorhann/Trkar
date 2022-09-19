<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class Store extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LaratrustUserTrait,LogsActivity;

    protected $fillable = [
        'email',
        'phone',
        'image',
        'address',
        'longitude',
        'latitude',
        'banner',
        'name_ar',
        'name_en',
        'vendor_id',
        'uuid',
        'store_type_id',
        'description_ar',
        'description_en',
        'status',
        'country',
        'area',
        'approved',
        'city'
    ];

    // Activity Log
    protected static $logAttributes = [
        'email',
        'phone',
        'image',
        'address',
        'longitude',
        'latitude',
        'banner',
        'name_ar',
        'name_en',
        'vendor_id',
        'uuid',
        'store_type_id',
        'description_ar',
        'description_en',
        'status',
        'country',
        'area',
        'approved',
        'city'
    ];
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


    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' =>  'datetime',
    ];
    public function storeType()
    {
        return $this->belongsTo(StoreType::class, 'store_type_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function branches()
    {
        return $this->hasMany(StoreBranch::class, 'store_id');
    }
}

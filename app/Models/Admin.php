<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\LogOptions;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, LogsActivity;
    
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
        'username',
        'email',
        'password',
        'status',
        'uuid',
        'last_login',
        'language',
        'permission_group_id',
    ];
    protected $guard = 'admin';

    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'uuid',
        'last_login',
        'language',
        'permission_group_id',
    ];

    protected $hidden = [
        'password',
    ];




    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // Activity Log
    /*public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id',
                'username',
                'email',
                'password',
                'status',
                'uuid',
                'id',
                'permission_group_id'
            ]);
        // Chain fluent methods for configuration options
    }*/
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function permissionGroup()
    {
        return $this->belongsTo('App\Models\PermissionGroup', 'permission_group_id');
    }
}

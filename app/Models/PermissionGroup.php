<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class PermissionGroup extends Model
{
    //use LogsActivity;
    protected $table = 'permission_groups';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'name',
        'permissions',
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
        'name',
        'permissions'
    ];
    public function admins()
    {
        return $this->hasMany('App\Models\Admin', 'permission_group_id');
    }

    public function getPermissionsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setPermissionsAttribute($value)
    {
        if (!is_array($value) || empty($value)) {
            $value = [];
        }
        $this->attributes['permissions'] = implode(',', $value);
    }
}

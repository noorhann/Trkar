<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class VendorReject extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'vendor_id',
        'reject_status_id',

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
        'vendor_id',
        'reject_status_id'
    ];
    public function rejectStatus()
    {
        return $this->belongsTo(StoreType::class, 'reject_status_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}

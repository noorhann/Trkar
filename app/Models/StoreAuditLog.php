<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class StoreAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'discription',
        'model_type',
        'properties',
        'model_id',
        'store_id',

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
        'user_id',
        'discription',
        'model_type',
        'properties',
        'model_id',
        'store_id'
    ];
    public function model()
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}

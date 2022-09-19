<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class FlashDealProduct extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'flash_deal_id',
        'product_id',
        'discount',
        'discount_type',
        'details',

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
protected static $logAttributes = ['id',
                'flash_deal_id',
                'product_id',
                'discount',
                'discount_type',
                'details'
            ];
}

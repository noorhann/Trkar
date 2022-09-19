<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class OrderDetails extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'order_id',
        'branch_id',
        'product_id',
        'quantity',
        'price',
        'tax',
        'vendor_id',
        'product_number',
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
        'id',
        'order_id',
        'product_id',
        'quantity',
        'shipping_cost',
        'price',
        'tax',
        'vendor_id',
        'product_number',
        'store_id'
    ];
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function branch()
    {
        return $this->belongsTo(StoreBranch::class, 'branch_id');
    }
}

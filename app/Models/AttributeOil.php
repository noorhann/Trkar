<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class AttributeOil extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $table = 'attribute_oils';

    protected $fillable = [
        'parent_id',
        'value',
        'status',
        'attribute_id',
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
        'season_id',
        'parent_id',
        'value',
        'status',
        'attribute_id',
        'type_id'
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
    public function parent()
    {
        return $this->belongsTo(AttributeOil::class, 'parent_id');
    }
    public function getParentsAttribute()
    {
        $collection = collect([]);
        $parent = $this->parent;
        while ($parent) {
            $collection->push($parent);
            $parent = $parent->parent;
        }

        return $collection;
    }
}

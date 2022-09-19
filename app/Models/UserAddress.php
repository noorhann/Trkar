<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class UserAddress extends Model
{
  use HasFactory, SoftDeletes, LogsActivity;

  protected $fillable = [
    'postal_code',
    'user_id',
    'recipent_name',
    'recipent_phone',
    'recipent_email',
    'country_id',
    'city_id',
    'area_id',
    'address',
    'longitude',
    'latitude',
    'home_no',
    'floor_no',
    'status',
    'default',
  ];
  // Activity Log
  /*public function getActivitylogOptions(): LogOptions
      {
          return LogOptions::defaults()
              ->logOnly([
                'postal_code',
                'user_id',
                'recipent_name',
                'recipent_phone',
                'recipent_email',
                'country_id',
                'city_id',
                'area_id',
                'address',
                'longitude',
                'latitude',
                'home_no',
                'floor_no',
                'status',
                'default'
              ]);
      }*/
  // Activity Log
  protected static $logAttributes = [
    'postal_code',
    'user_id',
    'recipent_name',
    'recipent_phone',
    'recipent_email',
    'country_id',
    'city_id',
    'area_id',
    'address',
    'longitude',
    'latitude',
    'home_no',
    'floor_no',
    'status',
    'default',
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
}

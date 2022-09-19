<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class Product extends Model
{

    use  HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'uuid',
        'slug',
        'car_model_id',
        'car_engine_id',
        'subcategory_id',
        'status',
        'product_type_id',
        'serial_number',
        'name_en',
        'name_ar',
        'details_en',
        'details_ar',
        'price',
        'actual_price',
        'discount',
        'image',
        'category_id',
        'subcategory_id',
        'car_made_id',
        'year_id',
        'manufacturer_id',
        'original_country_id',
        'store_id',
        'approved',
        'OEN',
        'wishlist',
    ];

    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = $value ?? '0';
    }

    protected $casts = [
        'year_id' => 'array',
        'car_made_id' => 'array',
        'car_model_id' => 'array',
        'car_engine_id' => 'array',

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
        'uuid',
        'slug',
        'car_model_id',
        'car_engine_id',
        'subcategory_id',
        'status',
        'product_type_id',
        'serial_number',
        'name_en',
        'name_ar',
        'details_en',
        'details_ar',
        'price',
        'actual_price',
        'discount',
        'image',
        'category_id',
        'subcategory_id',
        'car_made_id',
        'year_id',
        'manufacturer_id',
        'original_country_id',
        'store_id',
        'approved',
        'OEN',
        'wishlist',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function car_made()
    {
        return $this->belongsTo(CarMade::class);
    }

    public function carEngine()
    {
        return $this->belongsTo(CarEngine::class, 'car_engine_id');
    }
    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }
    public function originalCountry()
    {
        return $this->belongsTo(OriginalCountry::class, 'original_country_id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function stores()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }
    public function quantities()
    {
        return $this->hasMany(ProductQuantity::class, 'product_id');
    }
    public function tags()
    {
        return $this->hasMany(ProductTag::class, 'product_id');
    }
    public function views()
    {
        return $this->hasMany(ProductView::class, 'product_id');
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }
    public function settings()
    {
        return $this->hasMany(ProductSetting::class, 'product_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function questions()
    {
        return $this->hasMany(ProductQuestion::class, 'product_id');
    }
    public function wholesales()
    {
        return $this->hasMany(ProductWholesale::class, 'product_id');
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function productAttribute()
    {
        return $this->hasOne(ProductAttribute::class, 'product_id');
    }
}

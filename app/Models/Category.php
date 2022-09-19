<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class Category extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;


    protected $fillable = [
        'name_en',
        'name_ar',
        'image',
        'slug',
        'parent_id',
        'order',
        'status',
        'subcategories'

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
        'name_en',
        'name_ar',
        'image',
        'slug',
        'parent_id',
        'order',
        'status'
    ];

    public function suncategory()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->orderBy('order', 'ASC');
    }
    //protected $appends = ['childs'];

    public function getParentsAttribute()
    {
        $collection = collect([]);
        $parent = $this->parent;
        while ($parent) {
            $collection->push($parent);
            $parent = $parent->parent;
        }
        /*if($parent == 0)
        {
            $collection->push($parent);
            $parent = $parent->parent;
        }*/

        return $collection;
    }

    public function managedIDs()
    {
        $this->managedIDsTree($this->id);
        return 'Note : you can show Tree';
    }
    private function managedIDsTree($categoryID)
    {
        $arrays = [];
        $getName = self::where('id', $categoryID)->first(['name_en']);
        $getParents = self::where('parent_id', $categoryID)->orderBy('order', 'ASC')->get(['id', 'name_en']);
        if (count($getParents)) {
            $arrays[$categoryID] = $getName->name_en;
        } else {
            $arrays[$categoryID] = $getName->name_en;
        }

        foreach ($getParents as $category) {
            $this->managedIDsTree($category->id);
        }
        return '';
    }


    public function childs()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->orderBy('order', 'ASC');
    }
}

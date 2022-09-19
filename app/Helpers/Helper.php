<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function path()
    {
        if (App::environment('production')) {
            return 'https://' . request()->getHttpHost() . '/storage';
        } elseif (App::environment('local')) {
            return 'http://' . request()->getHttpHost() . '/storage';
        }
    }
    public static function IDGenerator($table, $trow, $length = 4, $prefix)
    {
        $data = DB::table($table)->orderBy('id', 'desc')->first();
        if (!$data) {
            $og_length = $length;
            $last_number = '';
        } else {
            $code = substr($data->$trow, strlen($prefix) + 1);
            $actial_last_number = ($code / 1) * 1;
            $increment_last_number = ((int)$actial_last_number) + 1;
            $last_number_length = strlen($increment_last_number);
            $og_length = $length - $last_number_length;
            $last_number = $increment_last_number;
        }
        $zeros = "";
        for ($i = 0; $i < $og_length; $i++) {
            $zeros .= "0";
        }
        return $prefix . $zeros . $last_number;
    }
    public static function categoriesForSelect()
    {
        $data = \App\Models\Category::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Category')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function orderStatusesForSelect()
    {
        $data = \App\Models\OrderStatus::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Order Status')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function categoriesForSelectManufacturer()
    {
        $data = \App\Models\Category::get(['id', 'name_ar','name_en']);

        $returnData = [
            '' => __('Select Category')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name_ar.'('.$value->name_en.')';
        }
        return $returnData;
    }
    public static function permissionGroupForSelect()
    {
        $data = \App\Models\PermissionGroup::get(['id', 'name']);

        $returnData = [
            '' => __('Select Permission Group')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function vendorForSelect()
    {
        $data = \App\Models\Vendor::get(['id', 'username as name']);

        $returnData = [
            '' => __('Select Vendor')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function storeForSelect()
    {
        $data = \App\Models\Store::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Store')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function rejectStatusForSelect()
    {
        $data = \App\Models\RejectStatus::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Reject Status')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function vendorStaffForSelect()
    {
        $data = \App\Models\VendorStaff::get(['id', 'username  as name']);

        $returnData = [
            '' => __('Select Vendor Staff')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function storeTypeForSelect()
    {
        $data = \App\Models\StoreType::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Store Type')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function parentCategoriesForSelect()
    {
        $data = \App\Models\Category::where('parent_id', 0)->get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Category')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function carMadesForSelect()
    {
        $data = \App\Models\CarMade::join('categories', 'car_mades.category_id', 'categories.id')
            ->get(['car_mades.id', 'car_mades.name_' . \App::getLocale() . ' as carMadeName', 'categories.name_' . \App::getLocale() . ' as CategoryName']);

        $returnData = [
            '' => __('Select Made')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->carMadeName . ' - ' . '( ' . $value->CategoryName . ' )';
        }
        return $returnData;
    }
    public static function manufacturersForSelect()
    {
        $data = \App\Models\Manufacturer::join('categories', 'manufacturers.category_id', 'categories.id')
            ->get(['manufacturers.id', 'manufacturers.name_' . \App::getLocale() . ' as manufacturerName', 'categories.name_' . \App::getLocale() . ' as CategoryName']);

        $returnData = [
            '' => __('Select Manufacturer')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->manufacturerName . ' - ' . '( ' . $value->CategoryName . ' )';
        }
        return $returnData;
    }
    public static function carModelForSelect()
    {
        $data = \App\Models\CarModel::join('car_mades', 'car_models.car_made_id', 'car_mades.id')
            ->join('categories', 'car_mades.category_id', 'categories.id')
            ->get(['car_models.id', 'car_models.name_' . \App::getLocale() . ' as carModelName', 'car_mades.name_' . \App::getLocale() . ' as carMadeName',  'categories.name_' . \App::getLocale() . ' as CategoryName']);
        $returnData = [
            '' => __('Select Model')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] =  $value->carModelName . ' - ( ' . $value->carMadeName . ' ) - ' . '( ' . $value->CategoryName . ' )';
        }
        return $returnData;
    }
    public static function carEnginesForSelect()
    {
        $data = \App\Models\CarEngine::join('car_models', 'car_engines.car_model_id', 'car_models.id')
            ->join('car_mades', 'car_models.car_made_id', 'car_mades.id')
            ->join('categories', 'car_mades.category_id', 'categories.id')
            ->get(['car_engines.id', 'car_engines.name_' . \App::getLocale() . ' as carEngineName', 'car_models.name_' . \App::getLocale() . ' as carModelName', 'car_mades.name_' . \App::getLocale() . ' as carMadeName',  'categories.name_' . \App::getLocale() . ' as CategoryName']);
        $returnData = [
            '' => __('Select Model')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] =  $value->carModelName . ' - ( ' . $value->carMadeName . ' ) - ' . '( ' . $value->CategoryName . ' )';
        }
        return $returnData;
    }
    public static function yearsForSelect()
    {
        $data = \App\Models\Year::get(['id', 'year']);

        $returnData = [
            '' => __('Select Year')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->year;
        }
        return $returnData;
    }
    public static function originalCountriesForSelect()
    {
        $data = \App\Models\OriginalCountry::get(['id', 'name_' . \App::getLocale() . ' as CountryName']);

        $returnData = [
            '' => __('Select Original Country')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->CountryName;
        }
        return $returnData;
    }
    public static function storesForSelect()
    {
        $data = \App\Models\Store::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Store')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function countryForSelect()
    {
        $data = \App\Models\Country::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Country')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function attributeTyreForSelect()
    {
        $data = \App\Models\AttributeTyre::get(['id', 'value as name']);

        $returnData = [
            '' => __('Select Attribute Tyre')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    // attributeForSelect Tyre
    public static function attributeForSelect()
    {
        $data = \App\Models\Attribute::where('id','<=',7)->get(['id', 'name']);

        $returnData = [
            '' => __('Select Attribute')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function attributeOilForSelect()
    {
        $data = \App\Models\Attribute::where('id','>=',7)->get(['id', 'name']);

        $returnData = [
            '' => __('Select Attribute')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function seasonForSelect()
    {
        $data = \App\Models\Season::where('id','!=','4')->get(['id', 'name']);

        $returnData = [
            '' => __('Select Season')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function typeForSelect()
    {
        $data = \App\Models\TyreType::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Type')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function cityForSelect()
    {
        $data = \App\Models\City::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select City')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function areaForSelect()
    {
        $data = \App\Models\Area::get(['id', 'name_' . \App::getLocale() . ' as name']);

        $returnData = [
            '' => __('Select Area')
        ];
        foreach ($data as $key => $value) {
            $returnData[$value->id] = $value->name;
        }
        return $returnData;
    }
    public static function tablePagination()
    {
        return new \App\Libs\TablePagination();
    }
    public static function quickRandom($length = 16)
    {
        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public static function adminCan($routeName)
    {
        static $adminPermission;
        if (!$adminPermission) {
            $adminPermission = \Auth::user()->permissionGroup->permissions;
        }

        $adminPermission = array_merge($adminPermission, [
            // 'admin.auth.logout',
        ]);
        if (in_array($routeName, $adminPermission)) return true;

        return false;
    }
    public static function menu(array $permission = [])
    {
        static $staffPermission;
        if (!$staffPermission) {
            $staffPermission = \Auth::user()->permission_group->permissions;
        }

        foreach ($permission as $key => $value) {
            if (in_array($value, $staffPermission)) return true;
        }

        return false;
    }
    public static function menuActive(array $routes = [])
    {
        static $staffPermission;
        if (!$staffPermission) {
            $staffPermission = \Auth::user()->permissionGroup->permissions;
        }

        foreach ($routes as $key => $value) {
            if (in_array($value, $staffPermission)) return 'important-hidden';
        }

        if (in_array(\Request::route()->getName(), $routes)) return 'active';
    }
}

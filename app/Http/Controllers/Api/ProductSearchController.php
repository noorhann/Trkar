<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductSearchController extends Controller
{
    public function search_filter(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Product::query();

      if($request->filled('keyword'))
      {
          $query->where(function ($query) use ($keyword)
              {
                  $query->select('products.*','categories.name_en as category_name_en','categories.name_ar as category_name_ar','categories.slug as category_slug',
                  'manufacturers.name_en as manufacturer_name_en','manufacturers.name_ar as manufacturer_name_ar',
                  'car_mades.name_en as  car_mades_name_en','car_mades.name_ar as  car_mades_name_ar','car_mades.slug as  car_mades_slug')
                  ->leftjoin('categories','categories.id','products.subcategory_id')
                  ->leftjoin('manufacturers','manufacturers.id','products.manufacturer_id')
                  ->leftjoin('car_mades','car_mades.id','products.car_made_id')
                  

                  ->whereHas('category', function($query) use($keyword) 
                  {
                          $query->where('categories.name_en', 'like', '%'.$keyword.'%')
                              ->orwhere('categories.name_ar', 'like', '%'.$keyword.'%')
                              ->orwhere('categories.slug', 'like', '%'.$keyword.'%');
                  })
                  
                  ->orwhereHas('manufacturer', function($query) use($keyword) 
                  {
                          $query->where('manufacturers.name_en', 'like', '%'.$keyword.'%')
                          ->orwhere('manufacturers.name_ar', 'like', '%'.$keyword.'%');
                  })
      
                  ->orwhereHas('car_made', function($query) use($keyword) 
                  {
                          $query->where('car_mades.name_en', 'like', '%'.$keyword.'%')
                          ->orwhere('car_mades.name_ar', 'like', '%'.$keyword.'%')
                          ->orwhere('car_mades.slug', 'like', '%'.$keyword.'%');
                  })
      
                  ->orWhere('products.name_en','LIKE','%'.$keyword.'%')
                  ->orWhere('products.name_ar','LIKE','%'.$keyword.'%')
                  ->orWhere('products.slug','LIKE','%'.$keyword.'%')
                  ->orWhere('products.OEN','LIKE','%'.$keyword.'%')
                  ->orWhere('products.details_en','LIKE','%'.$keyword.'%')
                  ->orWhere('products.details_ar','LIKE','%'.$keyword.'%')
                  ->orWhere('products.serial_number','LIKE','%'.$keyword.'%')
                  ->where('products.status','1')
                  ->where('products.product_type_id','1')
                  ;
                });
      }

        if ($request->filled('category'))
        {

            $category = array();
            if($request->category[0] != null){
                foreach ($request->category as $cat) {
                    array_push($category, $cat);
                }
            }  
            $query->where( function ($query) use ($category) {
                $query->whereIn('products.category_id', $category)                    
                        ->where('products.status','1');

            });
        }

        if ($request->filled('sub_category')) 
        {
            $sub_category = array();
            if($request->sub_category[0] != null){
                foreach ($request->sub_category as $cat) {
                    array_push($sub_category, $cat);
                }
            }
            $query->where( function ($query) use ($sub_category) {
                $query->whereIn('products.subcategory_id', $sub_category)
                        ->where('products.status','1');

            });
        }

        if ($request->filled('car_made')) 
        {
            $car_made =$request->input('car_made');
            
            $query->where( function ($query) use ($car_made) 
            {
                $query->where('car_made_id', 'like', '%' . $car_made . '%')
                    ->where('products.status','1');

            });
           
        }

        if ($request->filled('car_model')) {
            $car_model_id = $request->car_model;

            $query->where( function ($query) use ($car_model_id) {
                $query->where('car_model_id', 'like', '%' . $car_model_id . '%')
                ->where('products.status','1');

            });
        }

        if ($request->filled('manufacturer')) 
        {
            $manufacturer = array();
            if($request->manufacturer[0] != null){
                foreach ($request->manufacturer as $man) {
                    array_push($manufacturer, $man);
                }
            }
            $query->where( function ($query) use ($manufacturer) {
                $query->whereIn('manufacturer_id', $manufacturer)
                ->where('products.status','1');

            });
        }

        if ($request->filled('originalCountry')) {
            $originalCountry = array();
            if($request->originalCountry[0] != null){
                foreach ($request->originalCountry as $coun) {
                    array_push($originalCountry, $coun);
                }
            }
            $query->where( function ($query) use ($originalCountry) {
                $query->whereIn('original_country_id', $originalCountry)
                ->where('products.status','1');

            });
        }

        if ($request->filled('carEngine')) {
            $carEngine_id = $request->carEngine;

            $query->where( function ($query) use ($carEngine_id) {
                $query->where('car_engine_id', 'like', '%' . $carEngine_id . '%')
                ->where('products.status','1');

            });
        }

        if ($request->filled('years_id')) {

            $years_id = $request->years_id;

            $query->where( function ($query) use ($years_id) {
                $query->where('year_id', 'like', '%' . $years_id . '%')
                ->where('products.status','1');

            });
            
        }


        if ($request->filled('price')) {
            list($min, $max) = explode(",", $request->price);

            $query->where('price', '>=', $min)
                  ->where('price', '<=', $max)
                  ->where('products.status','1');

        }

        $page_size=$request->page_size ?? 10 ;

        $products = $query->paginate($page_size);

        return response()->json(['status'=>true,
                            'message'=>trans('search result '),
                            'code'=>200,
                            'data'=>$products,
            ],200);
 
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    public function product_by_category(Request $request,$id)
    {
        $Last_parent_id = Category::where('id',$id)->get()
                        ->map(function($category)
                        {
                            return $category->id;
                        })->toArray(); 

        $top_parent_id = Category::where('parent_id',$Last_parent_id)->get()
                        ->map(function($category)
                        {
                            return $category->id;
                        })->toArray();

        $first_parent_id = Category::whereIn('parent_id',$top_parent_id)->get()
                        ->map(function($category)
                        {
                                return $category->id;
                        })->toArray(); 
       
        $main_parent_id =  Category::whereIn('parent_id',$first_parent_id)->get()
                            ->map(function($category)
                            {
                                    return $category->id;
                            })->toArray(); 

        $CategoryArray=[$Last_parent_id,$top_parent_id,$first_parent_id , $main_parent_id ];
        $CategoryArray=Arr::collapse($CategoryArray);
       
        $page_size=$request->page_size ?? 10 ;
        if(auth()->guard('api')->check())
        { 
            $vendors = Vendor::where('email',auth('api')->user()->email)->first();
            if($vendors)
            {
                $product = Product::select('products.*','product_attributes.value')
                            ->join('stores','products.store_id','stores.id')
                            ->leftjoin('product_attributes','product_attributes.product_id','products.id')
                            ->whereIn('products.subcategory_id',$CategoryArray)
                            ->where('products.status',1)
                            ->where('stores.status',1)
                            ->orderby('products.id','desc')
                            ->paginate($page_size);
            }
            else
            {
                $product = Product::select('products.*','product_attributes.value')
                            ->join('stores','products.store_id','stores.id')
                            ->leftjoin('product_attributes','product_attributes.product_id','products.id')
                            ->whereIn('products.subcategory_id',$CategoryArray)
                            ->where('products.status',1)
                            ->where('stores.status',1)
                            ->orderby('products.id','desc')
                            ->paginate($page_size);
            }
            
        }
        
        

        if(!auth()->guard('api')->check())
        {
            $product = Product::select('products.*','product_attributes.value')
                            ->join('stores','products.store_id','stores.id')
                            ->leftjoin('product_attributes','product_attributes.product_id','products.id')
                            ->whereIn('products.subcategory_id',$CategoryArray)
                            ->where('products.product_type_id',1)
                            ->where('products.status',1)
                            ->where('stores.status',1)
                            ->orderby('products.id','desc')
                            ->paginate($page_size);
            foreach($product as $pro)
            {
                    $pro->wishlist='null';
                    $pro->save();     
            }

            return response()->json(['status'=>true,
                            'message'=>trans('products have been shown successfully '),
                            'code'=>200,
                            'data'=>$product,
            ],200);
        }

    
        foreach($product as $pro)
        {
            $child = Wishlist::where('user_id',auth('api')->user()->id)->where('product_id',$pro->id)->count();
                if($child != 0)
                {
                    $pro->wishlist='true';
                    $pro->save();
                }
                else
                {
                    $pro->wishlist='false';
                    $pro->save();
                }
        }

        
        return response()->json(['status'=>true,
                                'message'=>trans('products have been shown successfully '),
                                'code'=>200,
                                'data'=>$product,
                            ],200);
    }



    public function product_by_main_category(Request $request,$id)

    {
        $Last_parent_id = Category::where('id',$id)->value('id'); ///2107
        $top_parent_id = Category::where('parent_id',$Last_parent_id)->value('id'); //2106
        $first_parent_id = Category::where('parent_id',$top_parent_id)->value('id'); ///33
        $main_parent_id =  Category::where('parent_id',$first_parent_id)->value('id'); ///1

        $CategoryArray = [$Last_parent_id,$top_parent_id,$first_parent_id,$main_parent_id];
        $page_size=$request->page_size ?? 10 ;
        if(auth()->guard('api')->check())
        { 
            $vendors = Vendor::where('email',auth('api')->user()->email)->first();
            if($vendors)
            {
                     $product = Product::select('products.*','product_attributes.value')
                                                ->join('stores','products.store_id','stores.id')
                                                ->leftjoin('product_attributes','product_attributes.product_id','products.id')
                                                ->whereIn('products.category_id',$CategoryArray)
                                                ->where('products.status',1)
                                                ->where('stores.status',1)
                                                ->orderby('products.id','desc')
                                                ->paginate($page_size);
            }
            else
            {
                    $product = Product::select('products.*','product_attributes.value')
                                                ->join('stores','products.store_id','stores.id')
                                                ->leftjoin('product_attributes','product_attributes.product_id','products.id')
                                                ->whereIn('products.category_id',$CategoryArray)
                                                ->where('products.product_type_id',1)
                                                ->where('products.status',1)
                                                ->where('stores.status',1)
                                                ->orderby('products.id','desc')
                                                ->paginate($page_size);
            }
                                
        }

        if(!auth()->guard('api')->check())
        {
                    $product = Product::select('products.*','product_attributes.value')
                                ->join('stores','products.store_id','stores.id')
                                ->leftjoin('product_attributes','product_attributes.product_id','products.id')
                                ->whereIn('products.category_id',$CategoryArray)
                                ->where('products.product_type_id',1)
                                ->where('products.status',1)
                                ->where('stores.status',1)
                                ->orderby('products.id','desc')
                                ->paginate($page_size);
            foreach($product as $pro)
            {
                    $pro->wishlist='null';
                    $pro->save();     
            }

            return response()->json(['status'=>true,
                            'message'=>trans('products have been shown successfully '),
                            'code'=>200,
                            'data'=>$product,
            ],200);
        }

        foreach($product as $pro)
        {
            $child = Wishlist::where('user_id',auth('api')->user()->id)->where('product_id',$pro->id)->count();
                if($child != 0)
                {
                    $pro->wishlist='true';
                    $pro->save();
                }
                else
                {
                    $pro->wishlist='false';
                    $pro->save();
                }
        }

        return response()->json(['status'=>true,
                                'message'=>trans('app.product'),
                                'code'=>200,
                                'data'=>$product,
                            ],200);
    }

    public function filters(Request $request)
    {
        $query = Product::query();

        if ($request->filled('category')) {
            $categoryid = $request->category;

            $query->where( function ($query) use ($categoryid) {
                $query->where('category_id', $categoryid)                    
                        ->where('products.status','1');

            });
        }

        if ($request->filled('sub_category')) {
            $sub_category_id = $request->sub_category;

            $query->where( function ($query) use ($sub_category_id) {
                $query->where('subcategory_id', $sub_category_id)
                        ->where('products.status','1');

            });
        }

        if ($request->filled('car_made')) {
            $car_made_id = $request->car_made;

            $query->where( function ($query) use ($car_made_id) {
                $query->where('car_made_id', $car_made_id)
                ->where('products.status','1');

            });
        }


        if ($request->filled('car_model')) {
            $car_model_id = $request->car_model;

            $query->where( function ($query) use ($car_model_id) {
                $query->where('car_model_id', $car_model_id)
                ->where('products.status','1');

            });
        }

        if ($request->filled('manufacturer')) {
            $manufacturer_id = $request->manufacturer;

            $query->where( function ($query) use ($manufacturer_id) {
                $query->where('manufacturer_id', $manufacturer_id)
                ->where('products.status','1');

            });
        }

        if ($request->filled('originalCountry')) {
            $originalCountry_id = $request->originalCountry;

            $query->where( function ($query) use ($originalCountry_id) {
                $query->where('original_country_id', $originalCountry_id)
                ->where('products.status','1');

            });
        }

        if ($request->filled('carEngine')) {
            $carEngine_id = $request->carEngine;

            $query->where( function ($query) use ($carEngine_id) {
                $query->where('car_engine_id', $carEngine_id)
                ->where('products.status','1');

            });
        }

        if ($request->filled('years_id')) {
            $years_id = $request->input('years_id');
            
            $query->where(function ($query) use($years_id) {
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

       
        $result = $query->get();

        return response()->json(['status'=>true,
                                'message'=>'filter result',
                                'code'=>200,
                                'data'=>$result,
                            ],200);

    }

    public function same_day_delivary(Request $request)
    {
        $longitude = User::select('longitude')->where('id',auth('api')->id())->value('longitude');
        $latitude = User::select('latitude')->where('id',auth('api')->id())->value('latitude');
        $page_size=$request->page_size ?? 10 ;


            $product=Product::select('products.*',DB::raw("6371 * acos(cos(radians(" . $latitude . ")) 
                                        * cos(radians(stores.latitude)) 
                                        * cos(radians(stores.longitude) - radians(" . $longitude . ")) 
                                        + sin(radians(" .$latitude. ")) 
                                        * sin(radians(stores.latitude))) AS distance"))
                                        ->join('stores', 'stores.id','products.store_id')
                                        ->having('distance', "<", 20)
                                        ->where('products.status',1)
                                        ->where('stores.status',1)
                                        ->orderby('products.id','desc')
                                        ->paginate($page_size);
                                        
            return response()->json(['status'=>true,
                                        'message'=>trans('products have been shown successfully '),
                                        'code'=>200,
                                        'data'=>$product,
                                        ],200);
                                    

            
        
    }


}

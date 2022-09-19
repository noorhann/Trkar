<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Store;
use App\Models\Product;
use App\Models\Comptabile;
use App\Models\ProductTag;
use App\Models\ProductView;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\ProductQuantity;
use App\Models\ProductQuestion;
use App\Models\ProductAttribute;
use App\Models\ProductWholesale;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function create_product(Request $request)
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }
        $store= Store::where('vendor_id',auth('vendor')->user()->id)->first();

        $product =Product::where('serial_number',$request->get('serial_number'))
                        ->where('store_id',$store->id)    
                        ->onlyTrashed()->first();
        if($product)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.productExist'),
                'code'=>200,
                'data'=>$product,
            ],200);
        }
        $validator = Validator::make($request->all(), [
            //'product_type_id' => 'required|Integer',
            'serial_number' => 'required|string|unique:products,serial_number,NULL,id,deleted_at,NULL',
            'name_en' => 'required|string|between:2,100|unique:products,name_en,NULL,id,deleted_at,NULL',
            'name_ar' => 'required|string|between:2,100|unique:products,name_ar,NULL,id,deleted_at,NULL',
            'details_en' => 'nullable|string',
            'details_en' => 'nullable|string',
            'OEN' => 'nullable',
            'discount' => 'nullable',
            'image' => 'image',
            'category_id' => 'nullable|Integer',
            'subcategory_id' => 'nullable|Integer',
            'car_made_id' => 'nullable',
            'car_model_id' => 'nullable',
            'car_engine_id' => 'nullable',

            'year_id' => 'nullable',
            'manufacturer_id' => 'nullable',
            'original_country_id' => 'nullable|Integer',
            //'store_id' => 'required|Integer',
            'slug' => 'unique:products',

        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

        $product = new Product();
        $product->name_en=$request->name_en;
        $product->name_ar=$request->name_ar;
        $product->slug=Str::slug($request->get('name_en'));
        if($request->has('details_ar'))
        {
            $product->details_ar=$request->details_ar;
        }
        elseif(!$request->has('details_ar'))
        {
            $product->details_ar='NULL';
        }

        if($request->has('details_en'))
        {
            $product->details_en=$request->details_en;
        }
        elseif(!$request->has('details_en'))
        {
            $product->details_en='NULL';
        }

        if($request->has('OEN'))
        {
            $product->OEN=$request->OEN;
        }
        

        $product->price=$request->price ;
        $product->discount=$request->discount;
        $product->serial_number=$request->serial_number;
        $product->store_id=$store->id;
        $product->product_type_id=$store->store_type_id;
        if($product->product_type_id == 1)
        {
            $product->uuid = 'VR'.'_'.auth('vendor')->user()->id.'_'.$request->serial_number;

        }
        if($product->product_type_id == 2)
        {
            $product->uuid = 'VW'.'_'.auth('vendor')->user()->id.'_'.$request->serial_number;

        }

        if($product->product_type_id == 3)
        {
            $product->product_type_id = $request->product_type_id;
            if($request->product_type_id == 1)
            {
                $product->uuid = 'VR'.'_'.auth('vendor')->user()->id.'_'.$request->serial_number;

            }
            if($request->product_type_id == 2)
            {
                $product->uuid = 'VW'.'_'.auth('vendor')->user()->id.'_'.$request->serial_number;

            }
        }
               
        
        if($request->has('image'))
        {
            $product->image=Storage::disk('public')->put("product",  $request->file('image'));
        }
        elseif(!$request->has('image'))
        {
            $product->image='NULL';
        }
        
        if($request->has('category_id'))
        {
            $product->category_id=$request->category_id;
        }
        elseif(!$request->has('category_id'))
        {
            $product->category_id='NULL';
        }

        if($request->has('car_made_id'))
        {

            $car_made_id = array();
            if($request->car_made_id[0] != null){
                foreach ($request->car_made_id as $made) {
                    array_push($car_made_id, $made);
                }
            }
            $mades[]=json_encode($car_made_id);
            $product->car_made_id =  implode(',', $mades);
        }
        

        if($request->has('car_model_id'))
        {
            $car_model_id = array();
            if($request->car_model_id[0] != null){
                foreach ($request->car_model_id as $model) {
                    array_push($car_model_id, $model);
                }
            }
            $models[]=json_encode($car_model_id);
            $product->car_model_id =  implode(',', $models);        
        }
        

        if($request->has('car_engine_id'))
        {
            $car_engine_id = array();
            if($request->car_engine_id[0] != null){
                foreach ($request->car_engine_id as $engine) {
                    array_push($car_engine_id, $engine);
                }
            }
            $engines[]=json_encode($car_engine_id);
            $product->car_engine_id =  implode(',', $engines); 
        }
        

        if($request->has('manufacturer_id'))
        {
            $product->manufacturer_id=$request->manufacturer_id;
        }
        elseif(!$request->has('manufacturer_id'))
        {
            $product->manufacturer_id='NULL';
        }

        if($request->has('original_country_id'))
        {
            $product->original_country_id=$request->original_country_id;
        }
        elseif(!$request->has('original_country_id'))
        {
            $product->original_country_id='NULL';
        }
        
        
        if($request->has('year_id'))
        {
            $year_id = array();
            if($request->year_id[0] != null){
                foreach ($request->year_id as $year) {
                    array_push($year_id, $year);
                }
            }
            $years[]=json_encode($year_id);
            $product->year_id =  implode(',', $years);
        }
        elseif(!$request->has('year_id'))
        {
            $product->year_id='NULL';
        }
        
        

       

        $product->approved=0;
        $product->status=1;

        $product->save();

        if($product->product_type_id == 2)
        {

            $validator = Validator::make($request->all(), [
                'minimum_quntity' => 'Integer',
                'price' ,
        
            ]);
    
            if ($validator->fails()) 
            {
                return response()->json(['status'=>false,
                                        'message'=>$validator->errors(),
                                        'code'=>400],400);
            }
            $wholesale = new ProductWholesale();
            if($request->has('minimum_quntity'))
            {
                $wholesale->minimum_quntity = $request->minimum_quntity;
            }
            elseif(!$request->has('minimum_quntity'))
            {
                $product->minimum_quntity='NULL';
            }
            if($request->has('price'))
            {
                $wholesale->price = $request->wholesale_price;
            }
            elseif(!$request->has('price'))
            {
                $product->price='NULL';
            }
            $wholesale->product_id=$product->id;
            //$wholesale->price = $request->wholesale_price;
            $wholesale->save();



        }
        return response()->json([
            'status'=>true,
            'message'=>trans('app.product'),
            'code'=>200,
            'data'=>$product,
        ],200);
    }

    public function remove_soft($id)
    {
        $product =Product::where('id',$id)->onlyTrashed()->first();
        $product->deleted_at = null;
        $product->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.productRetrived'),
            'code'=>200,
            'data'=>$product,
        ],200);

    }

    public function index($id)
    {
        $product=Product::where('id',$id)->get();
        $att = ProductAttribute::where('product_id',$id)->get();
        $img = ProductImage::where('product_id',$id)->get();
        $tag = ProductTag::where('product_id',$id)->get();
        $wholesale= ProductWholesale::where('product_id',$id)->get(); 
        
        $qt = ProductQuantity::where('product_id',$id)->get();
        $review=ProductReview::where('product_id',$id)->get();
        $productQuestion = ProductQuestion::where('product_id',$id)->get();
        $views=ProductView::where('product_id',$id)->get();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.productDetails'),
            'code'=>200,
            'data'=>['product'=>$product,
                    'product attributes'=>$att,
                    'product images'=>$img,
                    'product tags'=>$tag,
                    'product wholesale'=>$wholesale,
                    'product quantity'=> $qt,
                    'product reviews'=>$review,
                    'product question'=>$productQuestion,
                    'product views'=>$views
                ],
        ],200);
        


            

    }

    public function vendor_products(Request $request)
    {
        $page_size=$request->page_size ?? 10 ;
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }
        $store = Store::where('vendor_id',auth('vendor')->user()->id)->first();
        $product=Product::where('store_id',$store->id)->paginate($page_size);
        return response()->json([
            'status'=>true,
            'message'=>trans('products have been shown successfully'),
            'code'=>200,
            'data'=>$product,
        ],200);
    }

    public function category_products(Request $request ,$id)
    {
        $page_size=$request->page_size ?? 10 ;
        $product=Product::where('subcategory_id',$id)->paginate($page_size);
        return response()->json([
            'status'=>true,
            'message'=>trans('products have been shown successfully'),
            'code'=>200,
            'data'=>$product,
        ],200);
    }

    public function delete($id)
    {
        $product =Product::where('id',$id)->first();
        if($product == null)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('No product found'),
                'code'=>200,
            ],200);
        }
        else
        {
            $product =Product::where('id',$id)->delete();
            $att = ProductAttribute::where('product_id',$id)->get();
            if($att)
            {
                $att = ProductAttribute::where('product_id',$id)->delete();
            }
            
            $img = ProductImage::where('product_id',$id)->get();
            if($img)
            {
                $img = ProductImage::where('product_id',$id)->delete();
            }

            $tag = ProductTag::where('product_id',$id)->get();
            if($tag)
            {
                $tag = ProductTag::where('product_id',$id)->delete();
            }

            $wholesale= ProductWholesale::where('product_id',$id)->get(); 
            if($wholesale)
            {
                $wholesale= ProductWholesale::where('product_id',$id)->delete(); 
            }

            

            $qt = ProductQuantity::where('product_id',$id)->get();
            if($qt)
            {
                $qt = ProductQuantity::where('product_id',$id)->delete();
            }

            $review=ProductReview::where('product_id',$id)->get();
            if($review)
            {
                $review=ProductReview::where('product_id',$id)->delete();
            }

            $productQuestion = ProductQuestion::where('product_id',$id)->get();
            if($productQuestion)
            {
                $productQuestion = ProductQuestion::where('product_id',$id)->delete();
            }

            $views=ProductView::where('product_id',$id)->get();
            if($views)
            {
                $views=ProductView::where('product_id',$id)->delete();
            }

            return response()->json([
                'status'=>true,
                'message'=>trans('app.productDelete'),
                'code'=>200,
            ],200);

        }
    }

    public function update(Request $request,$id)
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }
        $product = Product::where('id',$id)->first();
        if(!$product)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('No product found'),
                'code'=>200,
            ],200);
        }

        $validator = Validator::make($request->all(), [
            'serial_number' => [ 'string', 'unique:products,serial_number,NULL,id,deleted_at,NULL'.$product->id],
            'name_en' => [ 'string', 'unique:products,name_en,NULL,id,deleted_at,NULL'.$product->id],
            'name_ar' => [ 'string', 'unique:products,name_ar,NULL,id,deleted_at,NULL'.$product->id],

            'details_en' => 'string',
            'details_en' => 'string',
            //'actual_price' => 'required',
            //'price' => 'required',

            'discount' => 'nullable',
            'image' => 'image',
            'category_id' => 'Integer',
            'subcategory_id' => 'Integer',
            'car_made_id' => 'nullable',
            'car_model_id' => 'nullable',
            'car_engine_id' => 'nullable',

            'year_id',
            'manufacturer_id' ,
            'original_country_id' => 'Integer',
            'store_id' => 'Integer',
            'slug' => 'unique:products',

        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }
        if($request->has('name_en'))
        {
            $product->name_en=$request->input('name_en');
            $product->slug=Str::slug($request->input('name_en'));


        }
        if($request->has('name_ar'))
        {
            $product->name_ar=$request->input('name_ar');
        }

        if($request->has('details_ar'))
        {
            $product->details_ar=$request->input('details_ar');
        }

        if($request->has('details_en'))
        {
            $product->details_en=$request->input('details_en');
        }

        if($request->has('OEN'))
        {
            $product->OEN=$request->input('OEN');
        }

        if($request->has('discount'))
        {
            $product->discount=$request->input('discount');
        }

        if($request->has('serial_number'))
        {
            $product->serial_number=$request->input('serial_number');
            if($product->product_type_id == 1)
            {
                $product->uuid = 'VR'.'_'.auth('vendor')->user()->id.'_'.$request->serial_number;
    
            }
            if($product->product_type_id == 2)
            {
                $product->uuid = 'VW'.'_'.auth('vendor')->user()->id.'_'.$request->serial_number;
    
            }
        }

        if($request->has('price'))
        {
            $product->price=$request->price ;
        }

        if($product->product_type_id == 2)
        {
            $w = ProductWholesale::where('product_id',$id)->first();
            if($w)
            {
                if($request->has('price_wholesale'))
                {
                    $w->price = $request->input('price_wholesale');
                }
                if($request->has('minimum_quntity'))
                {
                    $w->minimum_quntity = $request->input('minimum_quntity');
                }
                $w->save();

            }
            if(!$w)
            {
                $validator = Validator::make($request->all(), [
                'minimum_quntity' => 'Integer',
                'price' ,
        
                ]);
    
                if ($validator->fails()) 
                {
                return response()->json(['status'=>false,
                                        'message'=>$validator->errors(),
                                        'code'=>400],400);
                }
                $wholesale = new ProductWholesale();
                if($request->has('minimum_quntity'))
                {
                $wholesale->minimum_quntity = $request->minimum_quntity;
                }
                elseif(!$request->has('minimum_quntity'))
                {
                $product->minimum_quntity='NULL';
                }
                if($request->has('price'))
                {
                    $wholesale->price = $request->wholesale_price;
                }
                elseif(!$request->has('price'))
                {
                    $product->price='NULL';
                }
                $wholesale->product_id=$id;    
                $wholesale->save();  
            }  
        }

        if($request->has('image'))
        {
            $uploadFolder = 'product';

            if($image = $request->file('image'))
            {
                //$image_uploaded_path = $image->store($uploadFolder, 'public');
                $product->image =Storage::disk('public')->put("stores",  $request->file('image'));
            }         
        }

        if($request->has('category_id'))
        {
            $product->category_id=$request->input('category_id');
        }

        if($request->has('subcategory_id'))
        {
            $product->subcategory_id=$request->input('subcategory_id');
        }

        if($request->has('car_made_id'))
        {
            $car_made_id = array();
            if($request->car_made_id[0] != null){
                foreach ($request->car_made_id as $made) {
                    array_push($car_made_id, $made);
                }
            }
            $mades[]=json_encode($car_made_id);
            $product->car_made_id =  implode(',', $mades);        }

        if($request->has('car_model_id'))
        {
            $car_model_id = array();
            if($request->car_model_id[0] != null){
                foreach ($request->car_model_id as $model) {
                    array_push($car_model_id, $model);
                }
            }
            $models[]=json_encode($car_model_id);
            $product->car_model_id =  implode(',', $models); 
        }

        if($request->has('car_engine_id'))
        {
            $car_engine_id = array();
            if($request->car_engine_id[0] != null){
                foreach ($request->car_engine_id as $engine) {
                    array_push($car_engine_id, $engine);
                }
            }
            $engines[]=json_encode($car_engine_id);
            $product->car_engine_id =  implode(',', $engines); 
        }

        if($request->has('year_id'))
        {
            $year_id = array();
            if($request->year_id[0] != null){
                foreach ($request->input('year_id') as $year) {
                    array_push($year_id, $year);
                }
            }
            $years[]=json_encode($year_id);
            $product->year_id =  implode(',', $years);
            //$product->year_id=$request->input('year_id');
        }

        if($request->has('manufacturer_id'))
        {
            $product->manufacturer_id=$request->input('manufacturer_id');
        }

        if($request->has('original_country_id'))
        {
            $product->original_country_id=$request->input('original_country_id');
        }

        if($request->has('store_id'))
        {
            $product->store_id=$request->input('store_id');
        }
        
        $product->save();
        
        return response()->json([
            'status'=>true,
            'message'=>trans('app.productUpdate'),
            'code'=>200,
            'data'=>$product,
        ],200);

    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        
        $product=Product::select('products.*','categories.name_en as category_name_en','categories.name_ar as category_name_ar','categories.slug as category_slug',
                                'manufacturers.name_en as manufacturer_name_en','manufacturers.name_ar as manufacturer_name_ar',
                                'car_mades.name_en as  car_mades_name_en','car_mades.name_ar as  car_mades_name_ar','car_mades.slug as  car_mades_slug')
                ->leftjoin('categories','categories.id','products.subcategory_id')
                ->leftjoin('manufacturers','manufacturers.id','products.manufacturer_id')
                ->leftjoin('car_mades','car_mades.id','products.car_made_id')

                ->whereHas('category', function($query) use($keyword) {
                    $query->where('categories.name_en', 'like', '%'.$keyword.'%')
                    ->orwhere('categories.name_ar', 'like', '%'.$keyword.'%')
                    ->orwhere('categories.slug', 'like', '%'.$keyword.'%');
                })
                ->orwhereHas('manufacturer', function($query) use($keyword) {
                    $query->where('manufacturers.name_en', 'like', '%'.$keyword.'%')
                    ->orwhere('manufacturers.name_ar', 'like', '%'.$keyword.'%');
                })
                ->orwhereHas('car_made', function($query) use($keyword) {
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

                ->orWhere(DB::raw('CONCAT_WS(" ",categories.name_en,manufacturers.name_en)'), 'like', '%' . $keyword . '%')
                ->orWhere(DB::raw('CONCAT_WS(" ",categories.name_en,car_mades.name_en)'), 'like', '%' . $keyword . '%')
                ->orWhere(DB::raw('CONCAT_WS(" ",categories.name_en,products.serial_number)'), 'like', '%' . $keyword . '%')
                ->orWhere(DB::raw('CONCAT_WS(" ",products.serial_number,manufacturers.name_en)'), 'like', '%' . $keyword . '%')

                ->get();

           
            return response()->json([
                    'status'=>true,
                    'message'=>trans('search result'),
                    'code'=>200,
                    'data'=>$product,
                ],200);
        
        
  

    }

    public function recent_products(Request $request)
    {
        $page_size=$request->page_size ?? 10 ;
         
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }

        $store = Store::where('vendor_id',auth('vendor')->user()->id)->first();
        $product=Product::where('store_id',$store->id)->orderby('id','desc')
                ->paginate($page_size);
        
       return response()->json([
                    'status'=>true,
                    'message'=>trans('recently addedd products'),
                    'code'=>200,
                    'data'=>$product,
                ],200);
        

    }

    
    public function best_sellers(Request $request)
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }
        $page_size=$request->page_size ?? 10 ;
        $order=Order::select('products.*',DB::raw('SUM(order_details.quantity) as total_sold_quantity'))
                ->join('order_details','order_details.order_id','orders.id')
                ->join('products','products.id','order_details.product_id')
                ->where('order_details.vendor_id',auth('vendor')->user()->id)
                ->groupBy('product_id') 
                ->orderby('total_sold_quantity','desc')
                ->paginate($page_size);
        
       return response()->json([
                    'status'=>true,
                    'message'=>trans('recently addedd products'),
                    'code'=>200,
                    'data'=>$order,
                ],200);
        

    }

    public function change_status($id)
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }
        
        $product = Product::where('id',$id)->first();
        if(!$product)
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('No product found'),
                'code'=>404,
            ],404);
        }

        if($product->status == '0')
        {
            $product->status = '1';
            $product->save();

            return response()->json([
                'status'=>true,
                'message'=>trans('app.productActive'),
                'code'=>200,
                'data'=>$product,
            ],200);

        }

        if($product->status == '1')
        {
            $product->status = '0';
            $product->save();

            return response()->json([
                'status'=>true,
                'message'=>trans('app.productActive'),
                'code'=>200,
                'data'=>$product,

            ],200);

        }
        
    }
}

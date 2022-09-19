<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProductWholesale;
use App\Models\StoreBranch;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class CartController extends Controller
{
    public function index()
    {

        $cart=Cart::select('products.*','carts.*','product_wholesales.minimum_quntity' ,'product_wholesales.price as price_wholesale')->where('user_id',auth('api')->user()->id)
                ->leftjoin('products','products.id','carts.product_id')
                ->leftjoin('product_wholesales','product_wholesales.product_id','products.id')
                ->orderby('carts.created_at','DESC')
                ->get();
        

        return response()->json([
            'status'=>true,
            'message'=>trans('app.cart'),
            'code'=>200,
            'data'=>$cart,
        ],200);
    }

    public function Add_to_cart(Request $request,$id,$q)
    {  
        $longitude = User::select('longitude')->where('id',auth('api')->id())->value('longitude');
        $latitude = User::select('latitude')->where('id',auth('api')->id())->value('latitude');
    
        $cart=Cart::where('user_id',auth('api')->user()->id)->where('product_id',$id)->first();
        $product = Product::select('products.*','product_wholesales.minimum_quntity' ,'product_wholesales.price as price_wholesale')->leftjoin('product_wholesales','product_wholesales.product_id','products.id')->where('products.id',$id)->first();
        
        if(!$longitude || !$latitude)
        {
            $branch = StoreBranch::select('store_branches.*',DB::raw("6371 * acos(cos(radians(0)) 
                                * cos(radians(store_branches.latitude)) 
                                * cos(radians(store_branches.longitude) - radians(0)) 
                                + sin(radians(0)) 
                                * sin(radians(store_branches.latitude))) AS distance"))
                                //->join('stores', 'store_branches.store_id','stores.id')
                                ->join('product_quantities', 'product_quantities.branch_id','store_branches.id')
                                ->where('store_id',$product->store_id)
                                //->where('quantity','>',$cart->quantity)
                                //->having('distance', "<", 20)
                                ->orderby('distance','asc')
                                ->limit(1)
                                ->get();
            
        }
        else 
        {
            $branch = StoreBranch::select('store_branches.*',DB::raw("6371 * acos(cos(radians(" . $latitude . ")) 
                                * cos(radians(store_branches.latitude)) 
                                * cos(radians(store_branches.longitude) - radians(" . $longitude . ")) 
                                + sin(radians(" .$latitude. ")) 
                                * sin(radians(store_branches.latitude))) AS distance"))
                                //->join('stores', 'store_branches.store_id','stores.id')
                                ->join('product_quantities', 'product_quantities.branch_id','store_branches.id')
                                ->where('store_id',$product->store_id)
                                //->where('quantity','>',$cart->quantity)
                                //->having('distance', "<", 20)
                                ->orderby('distance','asc')
                                ->limit(1)
                                ->get();
        }
        $tax = Setting::where('key','tax')->first();
        if($cart == null)
        {

            $user_cart = new Cart();
            $user_cart->product_id = $id;
            $user_cart->user_id = auth('api')->user()->id ; 
            $user_cart->store_id = $product->store_id;
            $user_cart->discount = $product->discount;
            $user_cart->quantity = 1;
            $user_cart->tax = $tax->value;
            $price_with_tax = $product->price + $product->price *($tax->value / 100);
            $user_cart->price= $product->price;
            $user_cart->save();
            
            return response()->json([
                'status'=>true,
                'message'=>trans('app.AddToCart'),
                'code'=>200,
                'data'=>$user_cart,
                'branch'=>$branch,
                'wholesale'=>['minimum_quntity'=> $product->minimum_quntity ,'price'=>$product->price_wholesale]
            ],200);

        }
        else
        {
            if($q == '+')
            {
                $cart->update(['quantity'=>$cart->quantity + 1]); 
                $cart->save();
                return response()->json(['status'=>true,
                        'message'=>trans('app.AddToCart'),
                        'code'=>200,
                        'data'=>$cart,
                        'branch'=>$branch,
                        'wholesale'=>['minimum_quntity'=> $product->minimum_quntity ,'price'=>$product->price_wholesale]

                    ],200);
            }
            else
            {
                $cart->update(['quantity'=>$cart->quantity - 1]); 
                $cart->save();
                return response()->json(['status'=>true,
                        'message'=>trans('app.AddToCart'),
                        'code'=>200,
                        'data'=>$cart,
                        'branch'=>$branch,
                        'wholesale'=>['minimum_quntity'=> $product->minimum_quntity ,'price'=>$product->price_wholesale]
                    ],200);
            }
        }

    }

    public function remove_from_cart($id)
    {
        $cart= Cart::where('user_id',auth('api')->user()->id)->where('product_id',$id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.removeCart'),
            'code'=>200,
        ],200);
    }

    public function mass_delete()
    {
        $cart= Cart::where('user_id',auth('api')->user()->id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.removeCart'),
            'code'=>200,
        ],200);
    }
}

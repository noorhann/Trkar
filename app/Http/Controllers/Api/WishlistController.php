<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
          $wishlist =Wishlist::where('user_id',auth('api')->user()->id)
          ->join('products','products.id','wishlist.product_id')   
          ->orderby('wishlist.created_at','DESC') 
          ->get();
          return response()->json([
            'status'=>true,
            'message'=>trans('app.wishlistView'),
            'code'=>200,
            'data'=>$wishlist,
        ],200);
    }
    
    public function add_to_wishlist($id)
    {
        if(!auth()->guard('api')->check())
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
                'message'=>trans('no product found'),
                'code'=>200,
            ],200);
        }

        $w =Wishlist::where('product_id',$id)->where('user_id',auth('api')->user()->id)->first();
        if($w) 
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('app.alreadyAdd'),
                'code'=>200,
          
            ],200);
        }
        $wishlist = new Wishlist();
        $wishlist->product_id = $id ; 
        $wishlist->user_id =auth('api')->user()->id;
        $wishlist->save();
        $product->wishlist='true';
        $product->save();
          return response()->json([
            'status'=>true,
            'message'=>trans('app.wishlist'),
            'code'=>200,
            'data'=>$wishlist,
        ],200);
    }

    public function delete($id)
    {
        $product = Wishlist::where('product_id',$id)->where('user_id',auth('api')->user()->id)->first();
        if(!$product) 
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('no product found in wishlist'),
                'code'=>404,
            ],404);
        }
        $wishlist =Wishlist::where('product_id',$id)->where('user_id',auth('api')->user()->id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.wishlistDelete'),
            'code'=>200,
        ],200);
    }

    public function mass_delete()
    {
        
        $wishlist =Wishlist::where('user_id',auth('api')->user()->id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.wishlistDelete'),
            'code'=>200,
        ],200);
    }
}

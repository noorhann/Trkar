<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Cart;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AbandonedCartController extends Controller
{
    public function abandonedCart()
    {
        $store= Store::where('vendor_id',auth('vendor')->user()->id)->first();
        $date =Carbon::now();
        $cart=Cart::where('store_id',$store->id)->where('carts.created_at','<',$date->subWeeks(2))
            ->join('users','users.id' ,'carts.user_id')
            ->get();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.abandonedCart'),
            'code'=>200,
            'data'=>$cart,
        ],200);
    }
}

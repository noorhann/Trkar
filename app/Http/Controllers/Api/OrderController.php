<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Shipping;
use App\Models\VendorUuid;
use App\Models\OrderStatus;
use App\Models\StoreBranch;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\ProductQuantity;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        
        if(!auth()->guard('api')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }
        
        $validator = Validator::make($request->all(), [
             
            'shipping_address_id' => 'required|Integer',
            'payment_method_id' => 'required|Integer',
            'shipping_company_id' => 'required|Integer',
            'shipping_cost' => 'required|Integer',
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

        

        $tax = Setting::where('key','tax')->first();
        
        $order = new Order();
        $order->user_id = auth('api')->user()->id;
        $order->shipping_address_id = $request->shipping_address_id;
        //$order->category_id = $request->category_id;
        //$order->subcategories_id = $request->subcategories_id;
        $order->date = Carbon::now();
        $order->order_status_id = 2;
        $order->payment_method_id = $request->payment_method_id;
        $order->shipping_company_id = $request->shipping_company_id;
        $order->shipping_cost = $request->shipping_cost;
        $order->discount = $request->discount;

        $order->tax = $tax->value;

        $order->save();

        return response()->json(['status'=>true,
            'message'=>trans('app.order'),
            'code'=>200,
            'order'=>$order,
        ],200);

        

    }

    public function order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string',
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
               
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

        $product = Product::where('id',$request->product_id)->first();
        if(!$product)
        {
            return response()->json(['status'=>false,
                                    'message'=>'no product',
                                    'code'=>400,
                                     'data'=>$product],400);
        }
        $store = Store::where('id',$product->store_id)->first();
        $vendor = Vendor::where('id',$store->vendor_id)->first();
        $uuid =VendorUuid::where('id',$vendor->vendor_uuids_id)->first();


        $order = new OrderDetails();
       

        $order->order_id=$request->order_id;
        $order->product_id=$request->product_id;
        $order->quantity=$request->quantity;

        $order->price=$product->discount  ; 
        $order->store_id =$product->store_id;
        $order->vendor_id=$store->vendor_id;
        $order->product_number= $uuid->retail;

        $order->save();

        return response()->json(['status'=>true,
                                'message'=>trans('Addedd successfully'),
                                'code'=>200,
                                'data'=>$order,
                            ],200);
    }

    public function order_number(Request $request,$id)
    {
        //$details=OrderDetails::where('order_id',$id)->get();
        $order=Order::where('id',$id)->first();
        $details1=OrderDetails::select(DB::raw('SUM(quantity*price) as total'))
        ->where('order_id',$id)
        ->groupBy('order_id') 
        
        ->get();
        
        $order->order_number=random_int(100000, 999999);
        //$with_discount = $details1[0]->total - $order->discount;
        $order->grand_total= $order->shipping_cost + ($details1[0]->total + ($details1[0]->total * ($order->tax/100)));
        $order->save();
        


        $longitude = User::select('longitude')->where('id',auth('api')->id())->value('longitude');
        $latitude = User::select('latitude')->where('id',auth('api')->id())->value('latitude');
        //$cart1=Cart::where('user_id',auth('api')->user()->id)->where('product_id',$id)->first();
        $products = OrderDetails::where('order_id',$id)->get();
        
        foreach($products as $product) {
                $branch = StoreBranch::select('store_branches.*',DB::raw("6371 * acos(cos(radians(" . $latitude . ")) 
                                * cos(radians(store_branches.latitude)) 
                                * cos(radians(store_branches.longitude) - radians(" . $longitude . ")) 
                                + sin(radians(" .$latitude. ")) 
                                * sin(radians(store_branches.latitude))) AS distance"))
                                ->join('product_quantities', 'product_quantities.branch_id','store_branches.id')
                                ->where('store_id',$product->store_id)
                                ->where('quantity','>',$product->quantity)
                                //->having('distance', "<", 20)
                                ->orderby('distance','asc')
                                ->limit(1)
                                ->get();
                

                $q = ProductQuantity::where('branch_id',$branch[0]->id)->first();
                $q->update(['quantity'=>$q->quantity - $product->quantity]); 
                $product->branch_id = $branch[0]->id;
                $product->save();
                $q->save();
        }





        $cart=Cart::where('user_id',auth('api')->user()->id)->delete();

        return response()->json(['status'=>true,
                                'message'=>trans('Addedd successfully'),
                                'code'=>200,
                                'data'=>$order,
                            ],200);

    }

    public function order($id)
    {
        $order = Order::select('orders.*','store_branches.name as branch_name',
                                'store_branches.address as branch_address','store_branches.branch_picked_address as branch_picked_address',
                                'store_branches.latitude as branch_latitude','store_branches.longitude as branch_longitude','store_branches.phone as branch_phone',
                                'user_addresses.*','order_details.*','products.*','order_statuses.name_'.app()->getLocale().' as order_status_name',
                                'shipping_companies.name_'.app()->getLocale().' as shipping_company_name','payment_methods.name_'.app()->getLocale().' as payment_method_name')
                    ->leftjoin('order_details','orders.id','order_details.order_id')
                    ->leftjoin('order_statuses','order_statuses.id','orders.order_status_id')
                    ->leftjoin('shipping_companies','shipping_companies.id','orders.shipping_company_id')
                    ->leftjoin('payment_methods','payment_methods.id','orders.payment_method_id')
                    ->leftjoin('products','products.id','order_details.product_id')
                    ->leftjoin('store_branches','store_branches.id','order_details.branch_id')
                    ->leftjoin('user_addresses','user_addresses.id','orders.shipping_address_id')
                    

                    //->leftjoin('product_quantities', 'product_quantities.branch_id','store_branches.id')

                    ->where('orders.id',$id)
                    ->get();
        $default = UserAddress::select('user_addresses.*')
                                ->where('user_id',$order[0]->user_id)
                                ->where('default','1')
                                ->get();

        return response()->json(['status'=>true,
                    'message'=>trans('Order showed successfully'),
                    'code'=>200,
                    'data'=>$order,
                    'default_address'=>$default,
                ],200);

    }

    public function history()
    {
        $order=Order::select('orders.*','order_details.*','products.*','order_statuses.name_'.app()->getLocale().' as order_status_name',
                            'shipping_companies.name_'.app()->getLocale().' as shipping_company_name','payment_methods.name_'.app()->getLocale().' as payment_method_name')
                    ->join('order_details','orders.id','order_details.order_id')
                    ->join('order_statuses','order_statuses.id','orders.order_status_id')
                    ->join('products','products.id','order_details.product_id')
                    ->join('shipping_companies','shipping_companies.id','orders.shipping_company_id')
                    ->join('payment_methods','payment_methods.id','orders.payment_method_id')
                    ->where('orders.user_id',auth('api')->user()->id)
                    ->orderby('orders.id','desc')
                    ->get();

        return response()->json(['status'=>true,
                    'message'=>trans('Order History showed successfully'),
                    'code'=>200,
                    'data'=>$order,
                ],200);

    }

    public function order_status($id)
    {
        $order=Order::select('orders.*','order_details.*','products.*','order_statuses.name_'.app()->getLocale().' as order_status_name',
                    'shipping_companies.name_'.app()->getLocale().' as shipping_company_name','payment_methods.name_'.app()->getLocale().' as payment_method_name')
                    ->join('order_details','orders.id','order_details.order_id')
                    ->join('products','products.id','order_details.product_id')
                    ->join('shipping_companies','shipping_companies.id','orders.shipping_company_id')
                    ->join('payment_methods','payment_methods.id','orders.payment_method_id')
                    ->join('order_statuses','order_statuses.id','orders.order_status_id')
                    ->where('orders.user_id',auth('api')->user()->id)
                    ->where('orders.order_status_id',$id)
                    ->orderby('orders.id','desc')
                    ->get();

        return response()->json(['status'=>true,
                    'message'=>trans('Order History showed successfully'),
                    'code'=>200,
                    'data'=>$order,
                ],200);
    }

    public function tax()
    {
        $tax = Setting::where('key','tax')->first();
        return response()->json(['status'=>true,
        'message'=>trans('tax  showed successfully'),
        'code'=>200,
        'data'=>$tax,
            ],200);
    } 

    public function status()
    {
        $status = OrderStatus::where('status',1)->get();
        return response()->json(['status'=>true,
        'message'=>trans('order status showed successfully'),
        'code'=>200,
        'data'=>$status,
            ],200);

    }

    public function changeStatus(Request $request,$order_id)
    {
        $order=Order::where('id',$order_id)->first();
        $order->order_status_id = $request->status_id;
        $order->save();

        return response()->json(['status'=>true,
                        'message'=>trans('order status showed successfully'),
                        'code'=>200,
                    'data'=>$order,
            ],200);
    }
}

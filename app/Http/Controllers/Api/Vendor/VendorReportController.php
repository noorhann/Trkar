<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VendorReportController extends Controller
{
    public function sales()
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }
        $tax =Setting::where('key','tax')->value('value');

        $order_details =OrderDetails::select(DB::raw('SUM(quantity*price) as total'),
                                        DB::raw('count(product_id) as number_of_products'),
                                        'order_id','created_at' )
                            ->where('vendor_id',auth('vendor')->user()->id)                    
                            ->groupby('order_id')    
                            ->get();

        return response()->json(['status'=>true,
                'message'=>trans('search result '),
                    'code'=>200,
                    'data'=>$order_details,
                    'tax'=>$tax,
            ],200);
    }

    public function mostSeller(Request $request)
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }

        $page_size=$request->page_size ?? 10 ;
        $order=Order::select('products.id','products.serial_number','products.name_'.app()->getLocale().' as name',
                    DB::raw('SUM(order_details.quantity) as total_sold_quantity'),
                    DB::raw('SUM(order_details.quantity)*(products.price) as total'))
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

    public function recentOrder()
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }    
        
    }

    public function VendorOrder()
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        } 

        $order=Order::select('orders.*','order_details.*','products.*','order_statuses.name_'.app()->getLocale().' as order_status_name',
                            'shipping_companies.name_'.app()->getLocale().' as shipping_company_name','payment_methods.name_'.app()->getLocale().' as payment_method_name')
                    ->join('order_details','orders.id','order_details.order_id')
                    ->join('order_statuses','order_statuses.id','orders.order_status_id')
                    ->join('products','products.id','order_details.product_id')
                    ->join('shipping_companies','shipping_companies.id','orders.shipping_company_id')
                    ->join('payment_methods','payment_methods.id','orders.payment_method_id')
                    ->where('order_details.vendor_id',auth('vendor')->user()->id)
                    ->orderby('orders.id','desc')
                    ->get();

        return response()->json(['status'=>true,
                    'message'=>trans('Order History showed successfully'),
                    'code'=>200,
                    'data'=>$order,
                ],200);
                            
    }

    public function VendorOrder_By_Status($status_id)
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        } 

        $order=Order::select('orders.*','order_details.*','products.*','order_statuses.name_'.app()->getLocale().' as order_status_name',
                            'shipping_companies.name_'.app()->getLocale().' as shipping_company_name','payment_methods.name_'.app()->getLocale().' as payment_method_name')
                    ->join('order_details','orders.id','order_details.order_id')
                    ->join('order_statuses','order_statuses.id','orders.order_status_id')
                    ->join('products','products.id','order_details.product_id')
                    ->join('shipping_companies','shipping_companies.id','orders.shipping_company_id')
                    ->join('payment_methods','payment_methods.id','orders.payment_method_id')
                    ->where('order_details.vendor_id',auth('vendor')->user()->id)
                    ->where('orders.order_status_id',$status_id)
                    ->orderby('orders.id','desc')
                    ->get();

        return response()->json(['status'=>true,
                    'message'=>trans('Order History showed successfully'),
                    'code'=>200,
                    'data'=>$order,
                ],200);
                            
    }


}

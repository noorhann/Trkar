<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Store;
use App\Models\Invoice;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function create(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|Integer',
            'Vat_no' => 'required|string',
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

        $order = Order::where('id', $request->order_id)->first();
        $details = OrderDetails::where('order_id',$order->id)->get();
        foreach ($details as $detail) 
        {
            $invoice_check = Invoice::where('order_id',$request->order_id)->where('vendor_id',$detail->vendor_id)->first();
            if(!$invoice_check)
            {
                $store = Store::where('id',$detail->store_id)->first();
                $invoice = new Invoice();   
                $invoice->order_id=$request->order_id;
                $invoice->Vat_no=$request->Vat_no;
                $invoice->branch_id = $detail->branch_id;
                $invoice->vendor_id = $detail->vendor_id;
                $invoice->invoice_number='#'.$detail->store_id.'_'. random_int(100000, 999999);
                $invoice->type=$store->store_type_id;
                $invoice->save();
            }

        }
        
        $invoices = Invoice::where('order_id',$request->order_id)->get();
        return response()->json([
            'status'=>true,
            'message'=>trans('invoices created successfully'),
            'code'=>201,
            'data'=>$invoices
        ],201);

    }

    public function view($id)
    {
        $invoice=Invoice::where('id',$id)->first();
        $order=Order::where('id',$invoice->order_id)->get();
        $details=OrderDetails::where('order_id',$order[0]->id)->get();
        
        return response()->json([
                    'status'=>true,
                    'message'=>trans('invoice showed successfully'),
                    'code'=>200,
                    'invoice'=>$invoice,
                    'order'=>$order,
                    'details'=>$details,

            ],200);
    }

    public function invoiceByOrder($order_id , $vendor_id)
    {
        $invoice=Invoice::where('order_id',$order_id)->where('vendor_id',$vendor_id)->first();
        $order=Order::where('id',$order_id)->get();
        $details=OrderDetails::join('products', 'products.id','order_details.product_id')
                        ->where('order_id',$order_id)->where('vendor_id',$vendor_id)->get();
        return response()->json([
            'status'=>true,
            'message'=>trans('invoice showed successfully'),
            'code'=>200,
            'invoice'=>$invoice,
            'order'=>$order,
            'details'=>$details,

    ],200);

    }

    public function invoiceByVendor( $vendor_id)
    {
        $invoice=Invoice::where('vendor_id',$vendor_id)->get();
        return response()->json([
            'status'=>true,
            'message'=>trans('invoices of vendor showed successfully'),
            'code'=>200,
            'invoice'=>$invoice,
            
    ],200);

    }

}

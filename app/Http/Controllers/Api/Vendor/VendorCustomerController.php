<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use Illuminate\Http\Request;

class VendorCustomerController extends Controller
{
    public function index(Request $request)
    {
        $page_size=$request->page_size ?? 10 ;

        $customers= OrderDetails::select('users.*')
                                ->leftjoin('orders','orders.id','order_details.order_id')
                                ->leftjoin('users','users.id','orders.user_id')
                                ->where('vendor_id',auth('vendor')->user()->id)
                                ->distinct()
                                ->paginate($page_size);
        
        return response()->json(['status'=>true,
                                'message'=>trans('vendor customers have been shown successfully '),
                                'code'=>200,
                                'data'=>$customers,
                ],200);
    }
}

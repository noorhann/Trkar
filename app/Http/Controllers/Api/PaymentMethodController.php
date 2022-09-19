<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $payment=PaymentMethod::where('status','1')->get();

        return response()->json(['status'=>true,
                'message'=>trans('Payment methods shown successfully'),
                'code'=>200,
                'data'=>$payment,
            ],200);
    }
}

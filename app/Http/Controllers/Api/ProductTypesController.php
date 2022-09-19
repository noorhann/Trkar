<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypesController extends Controller
{
    public function index()
    {
        $product = ProductType::select('id','name_'.app()->getLocale().' as name','created_at','updated_at','status')->get();
        return response()->json(['status'=>true,
                                'message'=>trans('app.productType'),
                                'code'=>200,
                                'data'=>$product,
                            ],200);
    }
}

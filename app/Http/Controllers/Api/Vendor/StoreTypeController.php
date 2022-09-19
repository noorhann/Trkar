<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\StoreType;
use Illuminate\Http\Request;

class StoreTypeController extends Controller
{
    public function index()
    {
        $store=StoreType::select('id','name_'.app()->getLocale().' as name','status')->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.store_type'),
                                'code'=>200,
                                'data'=>$store,
                            ],200);
    }
}

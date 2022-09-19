<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ShippingCompany;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ShippingCompaniesController extends Controller
{
    public function index()
    {
        $shipping = ShippingCompany::where('status','1')->get();

        return response()->json([
            'status' => true,
            'message' => trans('shipping companies showen successfully'),
            'code' => 200,
            'data' => $shipping,
        ], 200);
    }

    public function logo(Request $request, $id)
    {
        $image = ShippingCompany::where('id', $id)->first();
        $image->logo = Storage::disk('public')->put("logo",  $request->file('logo'));
        $image->save();
        return response()->json([
            'status' => true,
            'message' => trans('shipping companies logo addedd successfully'),
            'code' => 200,
            'data' => $image,
        ], 200);
    }
}

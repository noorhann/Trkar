<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductQuantity;
use Illuminate\Support\Facades\Validator;

class ProductQuantityController extends Controller
{
    public function index($id)
    {
        $qt = ProductQuantity::where('product_id',$id)->get();
        return response()->json([
            'status'=>true,
            'message'=>'ProductQuantity  shown successfully',
            'code'=>200,
            'data'=>$qt,
        ],200);


    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|Integer',
            'quantity_reminder' => 'required|Integer',
            'branch_id' => 'required|Integer',
            'product_id' => 'required|Integer',
    
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

       

        $qt = new ProductQuantity();
        $qt->quantity = $request->quantity;
        $qt->quantity_reminder = $request->quantity_reminder;
        $qt->branch_id = $request->branch_id;

        $qt->product_id = $request->product_id;
        $qt->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.productqt'),
            'code'=>200,
            'data'=>$qt,
        ],200);
    }

    
    public function delete($id)
    {
        $qt= ProductQuantity::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'ProductQuantity product deleted successfully',
            'code'=>200,
        ],200);
    }

    public function mass_delete($id)
    {
        $qt= ProductQuantity::where('product_id', $id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>'ProductQuantity of product deleted successfully',
            'code'=>200,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $qt= ProductQuantity::where('id',$id)->first();

        $qt->quantity=$request->input('quantity');
        $qt->quantity_reminder=$request->input('quantity_reminder');
        $qt->branch_id=$request->input('branch_id');

        $qt->save();

        return response()->json([
            'status'=>true,
            'message'=>'ProductQuantity  updated successfully',
            'code'=>200,
            'data'=>$qt,
        ],200);
    }
}
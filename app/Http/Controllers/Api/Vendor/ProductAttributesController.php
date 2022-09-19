<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\ProductTag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Validator;

class ProductAttributesController extends Controller
{
    public function index($id)
    {
        $att = ProductAttribute::where('product_id',$id)->get();
        return response()->json([
            'status'=>true,
            'message'=>'Attributes of product shown successfully',
            'code'=>200,
            'data'=>$att,
        ],200);


    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
            'product_id' => 'required|Integer',
    
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

       

        $att = new ProductAttribute();
        $att->value = $request->value;
        $att->product_id = $request->product_id;
        $att->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.productAtt'),
            'code'=>200,
            'data'=>$att,
        ],200);
    }

    
    public function delete($id)
    {
        $att= ProductAttribute::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Attribute deleted successfully',
            'code'=>200,
        ],200);
    }

    public function mass_delete($id)
    {
        $att= ProductAttribute::where('product_id', $id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Attributes of product deleted successfully',
            'code'=>200,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $att= ProductAttribute::where('product_id',$id)->first();

        $att->value=$request->value;
        $att->save();

        return response()->json([
            'status'=>true,
            'message'=>'att updated successfully',
            'code'=>200,
            'data'=>$att,
        ],200);
    }

    public function search_filter(Request $request)
    {
        $product = Product::get();
        $product = $product->newQuery();
        if ($request->has('width')) {
            $product->whereHas('product_attributes', function ($query) use ($request) {
                $query->whereIn('product_attributes.key', $request->input('key'));
            });
        }

    }
}

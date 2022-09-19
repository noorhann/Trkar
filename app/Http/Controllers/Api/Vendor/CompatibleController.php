<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comptabile;
use Illuminate\Support\Facades\Validator;


class CompatibleController extends Controller
{
    public function index($id)
    {
        $comp = Comptabile::where('product_id',$id)
            ->join('car_mades','car_mades.id' ,'comptabiles.car_made_id') 
            ->get();
        return response()->json([
            'status'=>true,
            'message'=>'Comptabile  products shown successfully',
            'code'=>200,
            'data'=>$comp,
        ],200);


    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_made_id' => 'required|Integer',
            'product_id' => 'required|Integer',
    
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

       

        $Comp = new Comptabile();
        $Comp->car_made_id = $request->car_made_id;
        $Comp->product_id = $request->product_id;
        $Comp->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.productComp'),
            'code'=>200,
            'data'=>$Comp,
        ],200);
    }

    
    public function delete($id)
    {
        $comp= Comptabile::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Comptabile product deleted successfully',
            'code'=>200,
        ],200);
    }

    public function mass_delete($id)
    {
        $comp= Comptabile::where('product_id', $id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Comptabile of product deleted successfully',
            'code'=>200,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $Comp= Comptabile::where('id',$id)->first();

        $Comp->car_made_id=$request->input('car_made_id');
        $Comp->save();

        return response()->json([
            'status'=>true,
            'message'=>'Comptabile products updated successfully',
            'code'=>200,
            'data'=>$Comp,
        ],200);
    }
}

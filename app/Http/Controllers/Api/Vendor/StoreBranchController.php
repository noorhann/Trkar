<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\StoreBranch;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StoreBranchController extends Controller
{
    public function index($id)
    {
        $branch =StoreBranch::where('store_id',$id)->get();
        return response()->json([
            'status'=>true,
            'message'=>trans('Store branchs have been shown successfully'),
            'code'=>200,
            'data'=>$branch,
        ],200);
    }

    public function delete($id)
    {
        $branch =StoreBranch::where('id',$id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('Store branchs have been deleted successfully'),
            'code'=>200,
            'data'=>$branch,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $branch =StoreBranch::where('id',$id)->first();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string','between:2,100'],
            'address' =>'required|string',
            'phone' => ['required', 'integer'],
            'longitude'=>'required',
            'latitude'=>'required',  
            'branch_picked_address'  =>'required|string',     
       
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

        $branch->name=$request->input('name');
        $branch->address=$request->input('address');
        $branch->phone=$request->input('phone');
        $branch->longitude=$request->input('longitude');
        $branch->latitude=$request->input('latitude');
        $branch->branch_picked_address=$request->input('branch_picked_address');

        $branch->save();
        return response()->json([
            'status'=>true,
            'message'=>trans('Store branchs have been updated successfully'),
            'code'=>200,
            'data'=>$branch,
        ],200);
    }

}

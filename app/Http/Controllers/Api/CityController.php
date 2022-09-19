<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index($id)
    {
        $cities= City::select('id','name_'.app()->getLocale().' as name','country_id','status')->where('deleted_at',NULL)->where('country_id',$id)->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.city'),
                                'code'=>200,
                                'data'=>$cities,
                            ],200);
    }

    public function all()
    {
        $cities=City::select('id','name_'.app()->getLocale().' as name','country_id','status')->where('deleted_at',NULL)->get();
        
        return response()->json(['status'=>true,
                                'message'=>trans('app.city'),
                                'code'=>200,
                                'data'=>$cities,
                            ],200);
    }

    public function get_city($id)
    {
        $cities= City::select('id','name_'.app()->getLocale().' as name','country_id','status')->where('id',$id)->first();

        return response()->json(['status'=>true,
                                'message'=>trans('city shown successfully'),
                                'code'=>200,
                                'data'=>$cities,
                            ],200);
    }

    
    public function create(Request $request)
    {
        $cities= City::create([
            'name_en'=>$request->get('name_en'),
            'name_ar'=>$request->get('name_ar'),
            'country_id'=>$request->get('country_id'),
            'status'=>$request->get('status'),

        ]);

        $cities->save();

        return response()->json([
            'status'=>true,
            'message'=>'city created successfully',
            'code'=>200,
            'data'=>$cities,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $cities= City::where('id',$id)->first();

        $cities->name_en=$request->input('name_en');
        $cities->name_ar=$request->input('name_ar');
        $cities->country_id=$request->input('country_id');
        $cities->status=$request->input('status');
        $cities->save();

        return response()->json([
            'status'=>true,
            'message'=>'City updated successfully',
            'code'=>200,
            'data'=>$cities,
        ],200);
    }

    public function delete($id)
    {
        $cities= City::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'City deleted successfully',
            'code'=>200,
        ],200);
    }
}

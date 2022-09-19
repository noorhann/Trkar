<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index($id)
    {
        $areas= Area::select('id','name_'.app()->getLocale().' as name','city_id','status')->where('deleted_at',NULL)->where('city_id',$id)->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.area'),
                                'code'=>200,
                                'data'=>$areas,
                            ],200);
    }

    public function all()
    {
        $areas=Area::select('id','name_'.app()->getLocale().' as name','city_id','status')->where('deleted_at',NULL)->get();
        
        return response()->json(['status'=>true,
                                'message'=>trans('app.area'),
                                'code'=>200,
                                'data'=>$areas,
                            ],200);
    }

    public function get_area($id)
    {
        $areas= Area::select('id','name_'.app()->getLocale().' as name','city_id','status')->where('id',$id)->first();

        return response()->json(['status'=>true,
                                'message'=>trans('area shown successfully'),
                                'code'=>200,
                                'data'=>$areas,
                            ],200);
    }

    
    public function create(Request $request)
    {
        $areas= Area::create([
            'name_en'=>$request->get('name_en'),
            'name_ar'=>$request->get('name_ar'),
            'city_id'=>$request->get('city_id'),
            'status'=>$request->get('status'),

        ]);

        $areas->save();

        return response()->json([
            'status'=>true,
            'message'=>'Area created successfully',
            'code'=>200,
            'data'=>$areas,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $areas= Area::where('id',$id)->first();

        $areas->name_en=$request->input('name_en');
        $areas->name_ar=$request->input('name_ar');
        $areas->city_id=$request->input('city_id');
        $areas->status=$request->input('status');
        $areas->save();

        return response()->json([
            'status'=>true,
            'message'=>'Area updated successfully',
            'code'=>200,
            'data'=>$areas,
        ],200);
    }

    public function delete($id)
    {
        $areas= Area::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Area deleted successfully',
            'code'=>200,
        ],200);
    }
}


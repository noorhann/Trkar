<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OriginalCountry;
use Illuminate\Http\Request;

class OriginalCountryController extends Controller
{
    public function all()
    {
        $car=OriginalCountry::select('id','name_'.app()->getLocale().' as name','status')->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.OriginalCountry'),
                                'code'=>200,
                                'data'=>$car,
                            ],200);
    }

    public function create(Request $request)
    {
    
        $car= OriginalCountry::create([
            'name_en'=>$request->get('name_en'),
            'name_ar'=>$request->get('name_ar'),
            'status'=>$request->get('status'),

        ]);

        $car->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('success'),
            'code'=>200,
            'data'=>$car,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $car= OriginalCountry::where('id',$id)->first();

        
        $car->name_en=$request->input('name_en');
        $car->name_ar=$request->input('name_ar');
        $car->status=$request->input('status');

        $car->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('success'),
            'code'=>200,
            'data'=>$car,
        ],200);
    }

    public function delete($id)
    {
        $car= OriginalCountry::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('success'),
            'code'=>200,
        ],200);
    }
}

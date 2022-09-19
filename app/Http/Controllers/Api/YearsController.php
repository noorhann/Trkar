<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class YearsController extends Controller
{
    public function all()
    {
        $car=Year::select('id','year')->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.year'),
                                'code'=>200,
                                'data'=>$car,
                            ],200);
    }

    public function create(Request $request)
    {
    
        $car= Year::create([
            'year'=>$request->get('year'),
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
        $car= Year::where('id',$id)->first();

        
        $car->year=$request->input('year');
        

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
        $car= Year::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('success'),
            'code'=>200,
        ],200);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $car=Year::where(function ($query) use($keyword) {
            $query->where('year', 'like', '%' . $keyword . '%');
            })->get();
           
        return response()->json([
                'status'=>true,
                'message'=>'search result',
                'code'=>200,
                'data'=>$car,
            ],200);
    }
}

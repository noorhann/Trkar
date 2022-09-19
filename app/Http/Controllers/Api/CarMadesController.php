<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarMade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CarMadesController extends Controller
{
    public function all()
    {
        $car=CarMade::select('car_mades.id','car_mades.name_'.app()->getLocale().' as name','car_mades.slug','car_mades.status','car_mades.image','car_mades.category_id','categories.name_'.app()->getLocale().' as category_name')
        ->join('categories','categories.id','car_mades.category_id')
        ->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.car'),
                                'code'=>200,
                                'data'=>$car,
                            ],200);
    }
    
    public function category_mades($id)
    {
        $car=CarMade::select('car_mades.id','car_mades.name_'.app()->getLocale().' as name','car_mades.slug','car_mades.status','car_mades.image')
        ->where('category_id',$id)
        ->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.car'),
                                'code'=>200,
                                'data'=>$car,
                            ],200);
    }

    public function create(Request $request)
    {
    
        $uploadFolder = 'car_mades';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        
        $car= CarMade::create([
            'name_en'=>$request->get('name_en'),
            'slug'=>Str::slug($request->get('name_en')),
            'name_ar'=>$request->get('name_ar'),
            'category_id'=>$request->get('category_id'),
            'image'=>Storage::disk('public')->url($image_uploaded_path),
            'status'=>1,
        ]);

        $car->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.car_create'),
            'code'=>200,
            'data'=>$car,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $car= CarMade::where('id',$id)->first();

        $uploadFolder = 'car_mades';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        
        $car->name_en=$request->input('name_en');
        $car->slug=Str::slug($request->input('name_en'));
        $car->name_ar=$request->input('name_ar');
        $car->status=$request->input('status');
        $car->category_id=$request->input('category_id');
        $car->image=Storage::disk('public')->url($image_uploaded_path);

        $car->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.car_update'),
            'code'=>200,
            'data'=>$car,
        ],200);
    }

    public function delete($id)
    {
        $car= CarMade::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.car_delete'),
            'code'=>200,
        ],200);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $car=CarMade::where(function ($query) use($keyword) {
            $query->where('slug', 'like', '%' . $keyword . '%')
               ->orWhere('name_ar', 'like', '%' . $keyword . '%')
               ->orWhere('name_en', 'like', '%' . $keyword . '%');
            })->get();
           
        return response()->json([
                'status'=>true,
                'message'=>'search result',
                'code'=>200,
                'data'=>$car,
            ],200);
    }
}

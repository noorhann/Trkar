<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ManufacturersController extends Controller
{
    public function all()
    {
        $car=Manufacturer::select('manufacturers.id','manufacturers.name_'.app()->getLocale().' as name','manufacturers.status'
        ,'manufacturers.image','manufacturers.company_name','manufacturers.address','manufacturers.website','manufacturers.category_id','categories.name_'.app()->getLocale().' as category_name')
        ->join('categories','categories.id','manufacturers.category_id')
        ->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.manu'),
                                'code'=>200,
                                'data'=>$car,
                            ],200);
    }

    public function manufacturers_of_category($id)
    {
        $car=Manufacturer::select('manufacturers.id','manufacturers.name_'.app()->getLocale().' as name','manufacturers.status'
        ,'manufacturers.image','manufacturers.company_name','manufacturers.address','manufacturers.website','manufacturers.category_id')
        ->where('category_id',$id)
        ->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.manu'),
                                'code'=>200,
                                'data'=>$car,
                            ],200);
    }



    public function create(Request $request)
    {
        $uploadFolder = 'manufacturers';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        
        $car= Manufacturer::create([
            'name_en'=>$request->get('name_en'),
            'name_ar'=>$request->get('name_ar'),
            'status'=>1,
            'category_id'=>$request->get('category_id'),
            'company_name'=>$request->get('company_name'),
            'address'=>$request->get('address'),
            'website'=>$request->get('website'),

            'image'=>Storage::disk('public')->url($image_uploaded_path),
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
        $car= Manufacturer::where('id',$id)->first();

        $uploadFolder = 'manufacturers';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        
        $car->name_en=$request->input('name_en');
        $car->name_ar=$request->input('name_ar');
        $car->status=1;
        $car->category_id=$request->input('category_id');
        $car->image=Storage::disk('public')->url($image_uploaded_path);
        $car->company_name=$request->input('company_name');
        $car->address=$request->input('address');
        $car->website=$request->input('website');
        $car->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('success'),
            'code'=>200,
            'data'=>$car,
        ],200);
    }


    public function update_image(Request $request,$id)
    {
        $car= Manufacturer::where('id',$id)->first();

        $uploadFolder = 'manufacturers';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');

        $car->image=Storage::disk('public')->url($image_uploaded_path);
        
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
        $car= Manufacturer::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('success'),
            'code'=>200,
        ],200);
    }

    public function get($id)
    {
        $car= Manufacturer::where('id', $id)->first();
        return response()->json([
            'status'=>true,
            'message'=>trans('success'),
            'code'=>200,
            'data'=>$car,
        ],200);
    }

    public function manufacturerByName($name)
    {
        $car= Manufacturer::where('name_en', $name)->first();
        return response()->json([
            'status'=>true,
            'message'=>trans('success'),
            'code'=>200,
            'data'=>$car,
        ],200);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $car=Manufacturer::where(function ($query) use($keyword) {
            $query->where('name_en', 'like', '%' . $keyword . '%')
               ->orWhere('name_ar', 'like', '%' . $keyword . '%');
            })->get();
           
        return response()->json([
                'status'=>true,
                'message'=>'search result',
                'code'=>200,
                'data'=>$car,
            ],200);
    }
}

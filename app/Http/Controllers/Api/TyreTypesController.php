<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TyreType;
use Illuminate\Support\Facades\Storage;
class TyreTypesController extends Controller
{
    public function all_types()
    {
        $types=TyreType::select('id','name_'.app()->getLocale().' as name','created_at','updated_at','image')->get();
        return response()->json(['status'=>true,
                                'message'=>trans('tyre types have been shown successfully'),
                                'code'=>200,
                                'data'=>$types,
                            ],200);
    }

    public function update(Request $request,$id)
    {
        $tyre= TyreType::where('id',$id)->first();

        $uploadFolder = 'TyreType';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        
        $tyre->image=Storage::disk('public')->url($image_uploaded_path);

        $tyre->save();
        return response()->json(['status'=>true,
                                'message'=>trans('tyre types have been updated successfully'),
                                'code'=>200,
                                'data'=>$tyre,
                            ],200);
    }
}

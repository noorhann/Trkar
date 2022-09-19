<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    public function index($id)
    {
        $img = ProductImage::where('product_id',$id)->get();
        return response()->json([
            'status'=>true,
            'message'=>'Images of product shown successfully',
            'code'=>200,
            'data'=>$img,
        ],200);
    }
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'product_id' => 'required|Integer',
       ]);

       if ($validator->fails()) 
       {
           return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
       }

       if(!$request->hasFile('image')) {
           return response()->json(
               ['upload_file_not_found'],
                400);
       }
       $files = $request->file('image'); 


       foreach ($files as $file) 
       {      

            foreach($request->image as $mediaFiles) {

               $uploadFolder = 'productImages';
               $image_uploaded_path = $mediaFiles->store($uploadFolder, 'public');
               $image=Storage::disk('public')->put("productImages",  $mediaFiles);
               $product_id = $request->product_id;

               $image1 = new ProductImage();
               $image1->image = $image;
               $image1->product_id = $product_id;
               $image1->save();
       }
       return response()->json([
           'status'=>true,
           'message'=>trans('Image stored successfully'),
           'code'=>200,
           'data'=>$image1,
       ],200); 
       
    }
             
   } 

      
   public function delete($id)
   {
       $img= ProductImage::where('id', $id)->firstorfail()->delete();
       return response()->json([
           'status'=>true,
           'message'=>'Image deleted successfully',
           'code'=>200,
       ],200);
   }

   public function mass_delete($id)
   {
       $img= ProductImage::where('product_id', $id)->delete();
       return response()->json([
           'status'=>true,
           'message'=>'Images of product deleted successfully',
           'code'=>200,
       ],200);
   }

   public function update(Request $request,$id)
   {
        $img= ProductImage::where('id',$id)->first();
        //$uploadFolder = 'productImages';

        if($image = $request->file('image'))
        {
           // $image_uploaded_path = $image->store($uploadFolder, 'public');
            $img->image =Storage::disk('public')->put("productImages",  $request->file('image'));
        }       
        $img->save();

       return response()->json([
           'status'=>true,
           'message'=>'img updated successfully',
           'code'=>200,
           'data'=>$img,
       ],200);
   }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\AttributeOil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EngineOilController extends Controller
{
    public function getViscosityGrade()
    {
        $oils = AttributeOil::where('parent_id',0)->get();
        return response()->json(
            ['status'=>true,
            'message'=>trans('SAE viscosity grades have been shown successfully'),
            'code'=>200,
            'data'=>$oils,
        ],200);
    }

    // get manufacturer data by Viscosity Grade
    public function getManufactuere($id)
    {
        $oils = AttributeOil::where('parent_id',$id)->where('attribute_id',7)->get();

        return response()->json(
            ['status'=>true,
            'message'=>trans('Manufacturers have been shown successfully'),
            'code'=>200,
            'data'=>$oils,
        ],200);

    }


    // get OEM data by manfacturer
    public function getOEM($id)
    {
        $oils = AttributeOil::where('parent_id',$id)->where('attribute_id',9)->get();

        return response()->json(
            ['status'=>true,
            'message'=>trans('OEM Approvel have been shown successfully'),
            'code'=>200,
            'data'=>$oils,
        ],200);

    }

       // get Spacification data by manfacturer or OEM approval
       public function getSpacification($id)
       {
           $oils = AttributeOil::where('parent_id',$id)->where('attribute_id',10)->get();
   
           return response()->json(
               ['status'=>true,
               'message'=>trans('OEM Approvel have been shown successfully'),
               'code'=>200,
               'data'=>$oils,
           ],200);
   
       }

       public function search(Request $request)
       {
            $query = Product::query();

            $validator = Validator::make($request->all(), [           
                'viscosity_grade_id'=>'required',
                'manufacturer_id'=>'required',     
            ]);

            if ($validator->fails()) 
            {
                return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
            }

            $manufacturer_id = $request->input('manufacturer_id');
            $viscosity_grade_id = $request->input('viscosity_grade_id');

            $query-> whereHas('productAttribute', function($query) use($viscosity_grade_id) 
            {
                $query->whereJsonContains('value',['viscosity_grade_id'=>$viscosity_grade_id]);
            })->whereHas('productAttribute', function($query) use($manufacturer_id) 
            {
                $query->whereJsonContains('value',['manufacturer_id'=>$manufacturer_id]);
            });

            if ($request->filled('OEM_id'))
            {
                $OEM_id = $request->input('OEM_id');
    
                $query-> whereHas('productAttribute', function($query) use($OEM_id) 
                {
                    $query->whereJsonContains('value',['OEM_id'=>$OEM_id]);
                });
    
            }
    
            if ($request->filled('specification_id'))
            {
                $specification_id = $request->input('specification_id');
    
                $query-> whereHas('productAttribute', function($query) use($specification_id) 
                {
                    $query->whereJsonContains('value',['specification_id'=>$specification_id]);
                });
    
            }    
    
            $page_size=$request->page_size ?? 10 ;
    
            $products = $query->paginate($page_size);
    
            return response()->json(['status'=>true,
                                'message'=>trans('search result '),
                                'code'=>200,
                                'data'=>$products,
                ],200);
       }

       public function create(Request $request)
       {
            
            $att= new AttributeOil();
            $att->attribute_id =$request->input('attribute_id');
            $att->value =$request->input('value');
            $att->parent_id =$request->input('parent_id');

            $att->save();
            return response()->json([
                'status'=>true,
                'message'=>trans('Attribute oil have been created  successfully'),
                'code'=>201,
                'data'=>$att,
            ],201);
       }

       public function update(Request $request,$id)
       {
            $att= AttributeOil::where('id',$id)->first();
            $att->value=$request->input('value');
            $att->parent_id=$request->input('parent_id');
            $att->attribute_id=$request->input('attribute_id');

            $att->save();

            return response()->json([
                'status'=>true,
                'message'=>trans('Attribute oil have been updated successfully'),
                'code'=>200,
                'data'=>$att,
            ],200);
       }

       public function getByID($id)
       {
           $product = AttributeOil::where('id',$id)->first();
           return response()->json(['status'=>true,
                            'message'=>trans('attribute oil '),
                            'code'=>200,
                            'data'=>$product,
            ],200);
       }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeTyre;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AttributeTyreController extends Controller
{
    public function create(Request $request)
    {
        $att= AttributeTyre::create([
            'season_id'=>$request->get('season_id'),
            'attribute_id'=>$request->get('attribute_id'),
            'value'=>$request->get('value'),
            'parent_id'=>$request->get('parent_id'),
            'type_id'=>$request->get('type_id'),

        ]);
        $att->save();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.atts_create'),
            'code'=>200,
            'data'=>$att,
        ],200);
    }
    
    public function get_by_parent($id)
    {
        $att = AttributeTyre::where('parent_id',$id)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    public function get_by_parent_attribute($id_att,$id)
    {
        $att = AttributeTyre::where('parent_id',$id)->where('attribute_id',$id_att)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    
    public function get_width_by_season($type_id,$id)
    {
        $att = AttributeTyre::where('type_id',$type_id)->where('season_id',$id)->where('attribute_id',1)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('Season id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    public function get_hight_by_width($type_id,$id)
    {
        $att = AttributeTyre::where('type_id',$type_id)->where('parent_id',$id)->where('attribute_id',2)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('Width id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    public function get_diameter_by_hight($type_id,$id)
    {
        $att = AttributeTyre::where('type_id',$type_id)->where('parent_id',$id)->where('attribute_id',3)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('height id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    public function get_manufactuere_by_width($id)
    {
        $att = AttributeTyre::where('parent_id',$id)->where('attribute_id',7)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('width id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    public function all_manufactuere($id)
    {
        $att = AttributeTyre::where('type_id',$id)->where('attribute_id',7)->get();
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        
    }

    public function get_load_by_width($id)
    {
        $att = AttributeTyre::where('parent_id',$id)->where('attribute_id',5)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('width id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    public function get_speed_by_width($id)
    {
        $att = AttributeTyre::where('parent_id',$id)->where('attribute_id',4)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('width id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    public function get_axle_by_width($id)
    {
        $att = AttributeTyre::where('parent_id',$id)->where('attribute_id',6)->get();
        if($att->count() > 0)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.att'),
                'code'=>200,
                'data'=>$att,
            ],200);
        }
        else
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('width id does not exist'),
                'code'=>404,
            ],404);
        } 
    }

    

    public function all_att()
    {
        $att= Attribute::get();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.att'),
            'code'=>200,
            'data'=>$att,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $att= AttributeTyre::where('id',$id)->first();
        $att->season_id=$request->input('season_id');
        $att->value=$request->input('value');
        $att->parent_id=$request->input('parent_id');
        $att->attribute_id=$request->input('attribute_id');
        $att->type_id=$request->input('type_id');

        $att->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('Attribute tyre have been updated successfully'),
            'code'=>200,
            'data'=>$att,
        ],200);
    }

    public function delete($id)
    {
        $att= AttributeTyre::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('Attribute tyre have been deleted successfully'),
            'code'=>200,
        ],200);
    }

    public function search(Request $request)
    {
        $query = Product::query();

        $validator = Validator::make($request->all(), [
            
            'width_id'=>'required',
            'height_id'=>'required', 
            'diameter_id'=>'required', 

               
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }
        $width_id = $request->input('width_id');
        $height_id = $request->input('height_id');
        $diameter_id = $request->input('diameter_id');

        $query-> whereHas('productAttribute', function($query) use($width_id) 
        {
                $query->whereJsonContains('value',['width_id'=>$width_id]);
        })->whereHas('productAttribute', function($query) use($height_id) 
        {
                $query->whereJsonContains('value',['height_id'=>$height_id]);
        })-> whereHas('productAttribute', function($query) use($diameter_id) 
        {
                $query->whereJsonContains('value',['diameter_id'=>$diameter_id]);
        });


        if ($request->filled('type_id'))
        {
            $type_id = $request->input('type_id');

            $query-> whereHas('productAttribute', function($query) use($type_id) 
            {
                $query->whereJsonContains('value',['type_id'=>$type_id]);
            });

        }

        if ($request->filled('season_id'))
        {
            $season_id = $request->input('season_id');

            $query-> whereHas('productAttribute', function($query) use($season_id) 
            {
                $query->whereJsonContains('value',['season_id'=>$season_id]);
            });

        }

        if ($request->filled('diameter_id'))
        {
            $diameter_id = $request->input('diameter_id');

            $query-> whereHas('productAttribute', function($query) use($diameter_id) 
            {
                $query->whereJsonContains('value',['diameter_id'=>$diameter_id]);
            });

        }

        if ($request->filled('speed_rating_id'))
        {
            $speed_rating_id = array();
            if($request->speed_rating_id[0] != null){
                foreach ($request->speed_rating_id as $load) {
                    array_push($speed_rating_id, $load);
                }
            }
            $query->orwhereHas('productAttribute', function($query) use($speed_rating_id) 
            {
                $query->orwhereJsonContains('value',['speed_rating_id'=>$speed_rating_id]);
            });

        }

        if ($request->filled('manufacturer_id'))
        {
            $manufacturer_id = array();
            if($request->manufacturer_id[0] != null){
                foreach ($request->manufacturer_id as $load) {
                    array_push($manufacturer_id, $load);
                }
            }
            $query->orwhereHas('productAttribute', function($query) use($manufacturer_id) 
            {
                $query->orwhereJsonContains('value',['manufacturer_id'=>$manufacturer_id]);
            });

        }

        if ($request->filled('load_index_id'))
        {
            $load_index_id = array();
            if($request->load_index_id[0] != null){
                foreach ($request->load_index_id as $load) {
                    array_push($load_index_id, $load);
                }
            }
            $query->orwhereHas('productAttribute', function($query) use($load_index_id) 
            {
                $query->orWhereJsonContains('value',['load_index_id'=>$load_index_id]);
            });

        }

        if ($request->filled('axle_id'))
        {
            $axle_id = array();
            if($request->axle_id[0] != null){
                foreach ($request->axle_id as $load) {
                    array_push($axle_id, $load);
                }
            }
            $query->orwhereHas('productAttribute', function($query) use($axle_id) 
            {
                $query->orwhereJsonContains('value',['axle_id'=>$axle_id]);
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

    public function getByID($id)
    {
        $product = AttributeTyre::where('id',$id)->first();
        return response()->json(['status'=>true,
                            'message'=>trans('attribute tyre '),
                            'code'=>200,
                            'data'=>$product,
            ],200);
    }
}

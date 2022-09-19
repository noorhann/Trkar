<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class CategoryController extends Controller
{
    public function all()
    {
        $cat=Category::select('id','name_'.app()->getLocale().' as name','slug','image','parent_id','status','subcategories')
                        ->where('status','1')
                        ->get();
        
        return response()->json(['status'=>true,
                                'message'=>trans('app.cat'),
                                'code'=>200,
                                'data'=>$cat,
                            ],200);
    }
    
    public function main()
    {
        $cat=Category::select('id','name_'.app()->getLocale().' as name','slug','image','parent_id','status')
                                ->where('parent_id',0)                        
                                ->where('status','1')
                                ->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.cat'),
                                'code'=>200,
                                'data'=>$cat,
                            ],200);
    }

    public function get_parent($id)
    {
        $name=collect([]);
        $cat=Category::where('id',$id)->where('status','1')->first();
        $parentCats = Category::where('status','1')->find($id)->parents->reverse()->push($cat);

        foreach ($parentCats as $path) 
        {
            $name->push($path->id);
        }
        return response()->json(['status'=>true,
            'message'=>trans('Parents category'),
            'code'=>200,
            'data'=>$name,
            ],200);   
        
  
    }

    public function get_parent_name($id)
    {
        $name=collect([]);
        $cat=Category::where('id',$id)->where('status','1')->first();
        $parentCats = Category::where('status','1')->find($id)->parents->reverse()->push($cat);

        foreach ($parentCats as $path) 
        {
            $name->push($path->name_en);
        }
        return response()->json(['status'=>true,
            'message'=>trans('Parents category names'),
            'code'=>200,
            'data'=>$name,
            ],200);   
        
  
    }

    public function create(Request $request)
    {
    
        $cat= Category::create([
            'name_en'=>$request->get('name_en'),
            'slug'=>Str::slug($request->get('name_en')).' - '. Str::quickRandom(),
            'name_ar'=>$request->get('name_ar'),
            'parent_id'=>$request->get('parent_id'),

            'image'=>Storage::disk('public')->put("categories",  $request->file('image')),
            
        ]);

        $cat->status=1;
        $cat->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.cat_create'),
            'code'=>200,
            'data'=>$cat,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $cat= Category::where('id',$id)->first();

        $cat->name_en=$request->input('name_en');
        $cat->slug=Str::slug($request->get('name_en')).' - '. Str::quickRandom() ;
        $cat->name_ar=$request->input('name_ar');
        $cat->parent_id=$request->input('parent_id');
        $cat->status=$request->input('status');    
        $cat->image =Storage::disk('public')->put("categories",  $request->file('image'));

        $cat->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.cat_update'),
            'code'=>200,
            'data'=>$cat,
        ],200);
    }

    public function delete($id)
    {
        $cat= Category::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.cat_delete'),
            'code'=>200,
        ],200);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $cat=Category::where(function ($query) use($keyword) {
            $query->where('slug', 'like', '%' . $keyword . '%')
               ->orWhere('name_ar', 'like', '%' . $keyword . '%')
               ->orWhere('name_en', 'like', '%' . $keyword . '%');
            })
            ->where('status',1)
            ->get();
           
        return response()->json([
                'status'=>true,
                'message'=>'search result',
                'code'=>200,
                'data'=>$cat,
            ],200);
    }

    public function get_sub($id)
    {
        $cat= Category::where('parent_id',$id)->where('status',1)->get();
        
        if($cat->count() != 0)
        {
            foreach($cat as $category)
            {
                $child = Category::where('parent_id',$category->id)->count();
                if($child != 0)
                {
                    $category->subcategories='true';
                    $category->save();
                }
                else
                {
                    $category->subcategories='false';
                    $category->save();
                }
            }
            return response()->json(['status'=>true,
                                    'message'=>trans('app.cat'),
                                    'code'=>200,
                                    'data'=>$cat,
                                ],200);
        }
        else
            {
                return response()->json(['status'=>false,
                                    'message'=>trans('app.not_cat'),
                                    'code'=>200,
                                    //'data'=>$cat,
                                ],200);
            }
    }

    public function parents_children()
    {
        $cat=Category::select('id','name_'.app()->getLocale().' as name','slug','image','parent_id','status','subcategories')->where('parent_id',0)
                    ->orwhere('parent_id',1)
                    ->orwhere('parent_id',2)
                    ->where('status',1)
                    ->get();
        /*foreach($cat as $category)
        {
            $child = Category::where('parent_id',$category->id)->count();
                if($child != 0)
                {
                    $category->subcategories='true';
                    $category->save();
                }
                else
                {
                    $category->subcategories='false';
                    $category->save();
                }
        }*/

        return response()->json(['status'=>true,
                                'message'=>trans('app.cat'),
                                'code'=>200,
                                'data'=>$cat,
                            ],200);
    }

    public function get($id)
    {
        $cat=Category::select('id','name_'.app()->getLocale().' as name','slug','image','parent_id','status')->where('id',$id)->where('status',1)->get();

        return response()->json(['status'=>true,
                                'message'=>trans('app.cat'),
                                'code'=>200,
                                'data'=>$cat,
                            ],200);
    }

    

}

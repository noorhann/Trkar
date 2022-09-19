<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        $page = Page::get();
        return response()->json(['status'=>true,
                                        'message'=>trans('Pages have been shown successfully '),
                                        'code'=>200,
                                        'data'=>$page,
                                        ],200);
    }

    public function update(Request $request,$id)
    {
        $page=Page::where('id',$id)->first();
        if($page)
        {
            $page->value=$request->input('value');
            $page->save();
            return response()->json(['status'=>true,
                            'message'=>trans('Pages have been shown successfully '),
                            'code'=>200,
                            'data'=>$page,
                        ],200);

        }
        else
        {
            return response()->json(['status'=>false,
                            'message'=>trans('no id found'),
                            'code'=>404,
                        ],404);
        }
    }

    public function get($id)
    {
        $page=Page::where('id',$id)->first();
        if($page)
        {
            return response()->json(['status'=>true,
                            'message'=>trans('Page have been shown successfully '),
                            'code'=>200,
                            'data'=>$page,
                        ],200);

        }
        else
        {
            return response()->json(['status'=>false,
                            'message'=>trans('no id found'),
                            'code'=>404,
                        ],404);
        }
    }
}

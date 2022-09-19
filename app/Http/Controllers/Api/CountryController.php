<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['role:user']);
    }
    public function index()
    {
        $country=Country::where('deleted_at',NULL)->select('id','name_'.app()->getLocale().' as name','country_code','iso3','numcode','phonecode','status','created_at','updated_at')->get();
        return response()->json(['status'=>true,
                                'message'=>trans('app.country'),
                                'code'=>200,
                                'data'=>$country,
                            ],200);
    }

    
    public function get_country($id)
    {

        $country = Country::where('id',$id)->select('id','name_'.app()->getLocale().' as name','country_code','iso3','numcode','phonecode','status','created_at','updated_at')->first();

        return response()->json(['status'=>true,
                                'message'=>trans('app.country'),
                                'code'=>200,
                                'data'=>$country,
                            ],200);

    }

    public function create(Request $request)
    {
        $country=Country::create([
            'name_en'=>$request->get('name_en'),
            'name_ar'=>$request->get('name_ar'),
            'iso3'=>$request->get('iso3'),
            'country_code'=>$request->get('country_code'),
            'numcode'=>$request->get('numcode'),
            'phonecode'=>$request->get('phonecode'),
            'status'=>$request->get('status'),

        ]);

        $country->save();

        return response()->json([
            'status'=>true,
            'message'=>'country created successfully',
            'code'=>200,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $country=Country::where('id',$id)->first();

        $country->name_en=$request->input('name_en');
        $country->name_ar=$request->input('name_ar');
        $country->iso3=$request->get('iso3');
        $country->country_code=$request->input('country_code');
        $country->numcode=$request->input('numcode');
        $country->phonecode=$request->input('phonecode');
        $country->status=$request->input('status');
        $country->save();

        return response()->json([
            'status'=>true,
            'message'=>'Country updated successfully',
            'code'=>200,
        ],200);
    }

    public function delete($id)
    {
        $country = Country::where('id', $id)->firstorfail()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Country deleted successfully',
            'code'=>200,
        ],200);
    }
}

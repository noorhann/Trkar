<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Setting;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    public function index()
    {
        $add=UserAddress::where('user_id',auth('api')->user()->id)->get();
   

        return response()->json(['status'=>true,
                'message'=>trans('Address of user'),
                'code'=>200,
                'data'=>$add,
            ],200);
    

    }

    public function create(Request $request)
    {
        
        if(!auth()->guard('api')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }

        $validator = Validator::make($request->all(), [
            'recipent_name' => 'required|string',
            'recipent_phone' => 'required|numeric',
            'recipent_email' => 'required|email',
            'country_id' => 'required',
            'city_id' => 'required',
            'area_id' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'postal_code' => 'required|Integer',
            'home_no' => 'required|Integer',
            'floor_no' => 'required|Integer',  
            
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

        $shippings = UserAddress::where('user_id',auth('api')->user()->id)->get();

        if($shippings)
        {
            $add = new UserAddress();
            $add->user_id =auth('api')->user()->id;
            $add->recipent_name=$request->recipent_name;
            $add->recipent_phone=$request->recipent_phone;
            $add->recipent_email=$request->recipent_email;
            $add->country_id=$request->country_id;
            $add->city_id=$request->city_id;
            $add->area_id=$request->area_id;
            $add->address=$request->address;
            $add->longitude=$request->longitude;
            $add->latitude=$request->latitude;
            $add->postal_code=$request->postal_code;
            $add->home_no=$request->home_no;
            $add->floor_no=$request->floor_no;
            $add->status=1;
            $add->save();
        }
        else
        {
            $add = new UserAddress();
            $add->user_id =auth('api')->user()->id;
            $add->recipent_name=$request->recipent_name;
            $add->recipent_phone=$request->recipent_phone;
            $add->recipent_email=$request->recipent_email;
            $add->country_id=$request->country_id;
            $add->city_id=$request->city_id;
            $add->area_id=$request->area_id;
            $add->address=$request->address;
            $add->longitude=$request->longitude;
            $add->latitude=$request->latitude;
            $add->postal_code=$request->postal_code;
            $add->home_no=$request->home_no;
            $add->floor_no=$request->floor_no;
            $add->status=1;
            $add->default='true';

            $add->save();
        }
        
        return response()->json(['status'=>true,
            'message'=>trans('user address has been addedd successfully'),
            'code'=>200,
            'shipping-address'=>$add
        ],200);

    }

    public function delete($id)
    {
        $address= UserAddress::where('id',$id)->delete();
        return response()->json(['status'=>true,
                'message'=>trans('Address has been deleted successfully'),
                'code'=>200,
            ],200);

    }

    public function update(Request $request,$id)
    {
        if(!auth()->guard('api')->check())
            {
                return response()->json(['status'=>false,
                'message'=>trans('app.validation_error'),
                'code'=>401],
                401);
            }
        $address= UserAddress::where('id',$id)->first();
        if($address)
        {

            $validator = Validator::make($request->all(), [
                'recipent_name' => 'required|string',
                'recipent_phone' => 'required|numeric',
                'recipent_email' => 'required|email',
                'country_id' => 'required',
                'city_id' => 'required',
                'area_id' => 'required',
                'address' => 'required',
                'longitude' => 'required|string',
                'latitude' => 'required|string',
                'postal_code' => 'required|Integer',
                'home_no' => 'required|Integer',
                'floor_no' => 'required|Integer',  
                
            ]);
    
            if ($validator->fails()) 
            {
                return response()->json(['status'=>false,
                                        'message'=>$validator->errors(),
                                        'code'=>400],400);
            }

            $address->recipent_name=$request->input('recipent_name');
            $address->recipent_phone=$request->input('recipent_phone');
            $address->recipent_email=$request->input('recipent_email');
            $address->country_id=$request->input('country_id');
            $address->city_id=$request->input('city_id');
            $address->area_id=$request->input('area_id');
            $address->longitude=$request->input('longitude');
            $address->latitude=$request->input('latitude');
            $address->postal_code=$request->input('postal_code');
            $address->home_no=$request->input('home_no');
            $address->floor_no=$request->input('floor_no');
            $address->address=$request->input('address');

            $address->save();

            return response()->json(['status'=>true,
                        'message'=>trans('user address has been updated successfully'),
                        'code'=>200,
                        'data'=>$address
                    ],200);

        }

        return response()->json(['status'=>false,
                        'message'=>trans('no address found'),
                        'code'=>404,
                    ],404);

    }

    public function set_default(Request $request,$id)
    {
        $add =UserAddress::where('user_id',auth('api')->user()->id)->where('id',$id)->first();
        $add->default='1';
        $add->save();

        $address=UserAddress::where('user_id',auth('api')->user()->id)->where('id','!=',$id)->get();
        foreach($address as $a)
        {
            $a->default='0';
            $a->save();
        }

        return response()->json(['status'=>true,
                        'message'=>trans('default address has been set'),
                        'code'=>200,
                        'default'=>$add,
                    ],200);
    }
}

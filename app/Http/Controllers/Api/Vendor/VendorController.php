<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\User;
use App\Models\Store;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    //function to switch from vendor account to its customer account 
    public function switch_to_user()
    {
        if(auth()->guard('vendor')->check())
        {
            
    
            $user = User::where('email',auth('vendor')->user()->email)->first();
            if ($user) 
            {
                $token = ($user = auth('api')->getProvider()->retrieveByCredentials(['email'=>$user->email]))? auth('api')->login($user)
                : false;

                return response()->json(['status'=>true,
                                        'message'=>trans('user data'),
                                        'code'=>200,
                                        'access_token'=>$token,
                                        'data'=>$user,
                                    ],200); 
            }

            return response()->json(['status'=>false,
                'message'=>trans(' no data found to this user in customer table '),
                'code'=>404,
                ],404); 
        }
    }

    //function to switch from user account to its vendor account 
    public function switch_to_vendor()
    {
        if(auth()->guard('api')->check())
        {
            
    
            $user = Vendor::where('email',auth('api')->user()->email)->first();
            if ($user) 
            {
                $token = ($user = auth('vendor')->getProvider()->retrieveByCredentials(['email'=>$user->email]))? auth('vendor')->login($user)
                : false;
                $store = Store::where('vendor_id',auth('vendor')->user()->id)->first();
                if($store)
                {
                    $user->store = 'true';
                    $user->save();
                }
                else{
                    $user->store = 'false';
                    $user->save();
                }
                return response()->json(['status'=>true,
                                        'message'=>trans('vendor data'),
                                        'code'=>200,
                                        'access_token'=>$token,
                                        'data'=>$user,
                                    ],200); 
            }
            

            return response()->json(['status'=>false,
                'message'=>trans(' no data found to this user in vendor table '),
                'code'=>404,
                ],404); 
        }
    }

    public function update(Request $request)
    {
            $vendor = Vendor::where('id',auth('vendor')->user()->id)->first();
            $validator = Validator::make($request->all(), [
                'username' => 'string|between:2,100',        
            ]);
    
            if ($validator->fails()) 
            {
                return response()->json(['status'=>false,
                                        'message'=>$validator->errors(),
                                        'code'=>400],400);
            }

            if($request->has('username'))
            {
                $vendor->username = $request->input('username');
            }

            if($request->has('password'))
            {
                $vendor->password = bcrypt($request->password);
            }
            

            $vendor->save();

            return response()->json(['status'=>true,
                                        'message'=>trans('app.vendorData'),
                                        'code'=>200,
                                        'data'=>$vendor,
                                    ],200); 
    
    }

    function get_guard(){
        if(Auth::guard('admin')->check())
            {return "admin";}
        elseif(Auth::guard('api')->check())
            {return "user";}
        elseif(Auth::guard('vendor')->check())
            {return "vendor";}
    }
}

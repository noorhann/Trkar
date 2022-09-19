<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForgetPasswordController extends Controller
{
    
    public function update_password(Request $request,$email )
    {
        $vendor_update = Vendor::where('email',$email)->first();
        if($vendor_update == null)
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('app.email_not_found'),
                'code'=>401,
            ],401);
        }
        $vendor_update->update([
            'password'=>bcrypt($request->password)
        ]);     
        $vendor_update->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.updatepassword'),
            'code'=>200,
        ],200);
    }
}

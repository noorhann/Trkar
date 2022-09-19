<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ResetPassword;

class ForgetPasswordController extends Controller
{
    public function verifiy($code, $email)
    {
        $reset = ResetPassword::where('email',$email)->first();

        if($reset->created_at > now()->addHour())
        {
            $reset->delete();
            return response()->json([
                'status'=>false,
                'message'=>trans('app.reset_code_expire'),
                'code'=>400],400);
        }

        if($code == $reset->code)
        {
            return response()->json([
                'status'=>true,
                'message'=>trans('app.success_reset'),
                'code'=>200,
            ],200);

        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('app.wrong_reset'),
                'code'=>400,
            ],400);
        }
        


        
    }

    public function update_password(Request $request,$email )
    {
        $user_update = User::where('email',$email)->first();
        
        $user_update->update([
            'password'=>bcrypt($request->password)
        ]);        
        
        $user_update->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.updatepassword'),
            'code'=>200,
        ],200);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use App\Helpers\Helper;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class AuthController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['register','Admin_login']]);
    
    }
    public function Admin_login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required|string|min:6',
        
        ]);
        if (auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = auth('admin')->user();
            if ($user->status != 1) {
                auth()->logout();
                return response()->json([
                    'status'=>false,
                    'message'=>trans('suspended user'),
                    'code'=>401],401);

            }
        }
        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
        }
        if (! $token = auth()->guard('admin')->attempt($validator->validated()))
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('error'),
                'code'=>401],401);
        }
        
        
        return $this->createNewToken($token);

    }

    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:2,100|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);


        if($validator->fails()){
            
            return response()->json(
                ['status'=>false,
                'message'=>$validator->errors(),
                'code'=>400],400);

        }

        $admin = new Admin();
        $admin->username = $request->username;
        
        $admin->email = $request->email;
        $admin->uuid = Helper::IDGenerator('admins', 'uuid', 4, 'A');
        $admin->password = bcrypt($request->password);
        $admin->status = 1;

        $admin->save();

        $token = auth('admin')->attempt($validator->validated());
        
        return $this->createNewToken($token);
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'status'=>true,
            'message'=>trans('app.token_success'),
            'code'=>201,
            'access_token' => $token,
            'token_type' => 'bearer',
            //'expires_in' => auth('admin')->factory()->getTTL() * 60,
            'data' => auth('admin')->user()
        ],201);
    }


}

<?php

namespace App\Http\Controllers\Api;

use Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vendor;
use App\Helpers\Helper;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class AuthController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['login', 'register','verifiy' , 'resend','forget_password']]);
    
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required|string|min:6',
        
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
        }

        if (! $token = auth()->guard('api')->attempt($validator->validated()))
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('app.log_in_error'),
                'code'=>401],401);
        }
        
        $user1=User::where('email',$request->email)->first();
            $vendor= Vendor::where('email',$request->email)->first();
            if($vendor)
            {
                $user1->vendor = 'true';
            }
            else
            {
                $user1->vendor = 'false';
            }
            $user1->last_login=Carbon::now();
            $user1->save();  
            
        
      
        if (auth()->guard('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
            
            
            $user = auth('api')->user();
            if ($user->in_block != Null) {
                auth('api')->logout();
                return response()->json([
                    'status'=>false,
                    'message'=>trans('app.blocked'),
                    'code'=>401],401);

            }
            if ($user->email_verified_at == Null) {
                //auth()->logout();
                return response()->json([
                        'status'=>false,
                        'message'=>trans('app.not_verified'),
                        'code'=>402,
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'data' => auth('api')->user(),
                    ],402);

            } 
        }
        
        
        $user = auth('api')->user();
              
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

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;

        $user->uuid = Helper::IDGenerator('users', 'uuid', 4, 'C');
        $user->password = bcrypt($request->password);
        $user->last_login=Carbon::now();

        $user->save();
        $token = auth()->guard('api')->attempt($validator->validated());
        
        
        try {     
            $code = mt_rand(1000, 9999);
          
            //User::where('email', $user->email )->update(['codeActive' => $code]);

            $myemail = $user->email ;
            $user->activation_code = $code;
            $user->save();

            $data = array('code'=>$code);

            Mail::send(['html'=>'ActivationCodeTemplete'], $data, function ($message) use ($myemail,$code) {
                    $message->to($myemail)
                    ->subject('Account activation code')
                    ->from('info@trkar.com');
                    //->setBody();
                    });
        } catch (Exception $e) {
           
        } catch (JWTException $e) {
      
        }
        return $this->createNewToken($token);
    }

    public function logout() 
    {
        auth('api')->logout();
        return response()->json(['status'=>true,'message'=>trans('app.logout_success'),'code'=>200],200);
    }

    public function refresh() 
    {
        
        return $this->createNewToken(auth('api')->refresh());
    
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'status'=>true,
            'message'=>trans('app.token_success'),
            'code'=>201,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'data' => auth('api')->user()
        ],201);
    }

    public function userProfile()
    {
        return response()->json([
            'status'=>true,
            'message'=>trans('app.userProfile'),
            'code'=>200,
            'data' => auth('api')->user()           
        ],200);
    }

    public function isValidToken(Request $request)
    {
        
            return response()->json([
                'status'=>true,
                'message'=>trans('app.valid'),
                'code'=>200,
                'data' => auth('api')->check()           
            ],200); 

    }

    public function verifiy($code, $email)
    {
        $user = User::where('email',$email)->first();
        if($user->email_verified_at != NULL)
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('app.email_verified'),
                'code'=>401],401);
        }
        else{
            if( $code == $user->activation_code)
            {
                $user->email_verified_at =Carbon::now();
                $user->save();
                return response()->json([
                    'status'=>true,
                    'message'=>trans('app.success_verifiy_email'),
                    'code'=>200],200);
            }
            else 
            {
                return response()->json([
                    'status'=>false,
                    'message'=>trans('app.wrong_code'),
                    'code'=>401],401);
            }
        }
    }

    public function resend($email)
    {
        $user=User::where('email',$email)->first();
        try {     
            $code = mt_rand(1000, 9999);
            $myemail = $user->email ;
            $user->activation_code = $code;
            $user->save();

            
            $data = array('code'=>$code);

            Mail::send(['html'=>'ActivationCodeTemplete'], $data, function ($message) use ($myemail,$code) {
                        $message->to($myemail)
                        ->subject('Account activation code')
                        ->from('info@trkar.com');
                        //->setBody();
                    });
            
            return response()->json([
                    'status'=>true,
                    'message'=>trans('app.activation_code'),
                    'code'=>200],200);
        } catch (Exception $e) {
           
        } catch (JWTException $e) {
      
        }
    }

    
    public function forget_password($email)
    {
        $user=User::where('email',$email)->first();
        if($user == null)
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('app.email_not_found'),
                'code'=>401,
            ],401);
        }
        ResetPassword::where('email',$email)->delete();
        
        try {     
            $code = mt_rand(1000, 9999);
            $user = new ResetPassword();
            $user->code = $code;
            $user->email=$email;
            $user->save();

            $data = array('code'=>$code);

            Mail::send(['html'=>'ForgetPasswordTemplete'], $data, function ($message) use ($email,$code) {
                    $message->to($email)
                    ->subject('Reset Password code')
                    ->from('info@trkar.com');
                        //->setBody();
                });
            
            return response()->json([
                    'status'=>true,
                    'message'=>trans('app.reset_code'),
                    'code'=>200],200);
            } 
            catch (Exception $e) {}
            catch (JWTException $e) {}
    }
}

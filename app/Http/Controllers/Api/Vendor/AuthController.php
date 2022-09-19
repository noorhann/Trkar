<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use App\Helpers\Helper;
use App\Models\Admin;
use App\Models\Store;
use App\Models\Vendor;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Storage;
class AuthController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['register','login','verifiy' , 'resend','forget_password']]);
    
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
        if (! $token = auth()->guard('vendor')->attempt($validator->validated()))
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('app.log_in_error'),
                'code'=>401],401);
        }
        if (auth()->guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = auth('vendor')->user();
            $user1=Vendor::where('email',$request->email)->first();
            $user1->last_login=Carbon::now();
            $user1->save();
            if ($user->approved != 1) {
                auth('vendor')->logout();
                return response()->json([
                    'status'=>false,
                    'message'=>trans('app.not_approved'),
                    'code'=>401],401);

            }
            if ($user->in_block != Null) {
                auth('vendor')->logout();
                return response()->json([
                    'status'=>false,
                    'message'=>trans('app.blocked'),
                    'code'=>401],401);

            }
            /*if ($user->phone_verified_at == Null) {
                auth('vendor')->logout();
                return response()->json([
                    'status'=>false,
                    'message'=>trans('Please verifiy your phone number'),
                    'code'=>401],401);

            }*/
            if ($user->email_verified_at == Null) {
                //auth()->logout();
                return response()->json([
                        'status'=>false,
                        'message'=>trans('app.not_verified'),
                        'code'=>402,
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'data' => auth('vendor')->user()
                    ],402);

            } 
        }
        
        
        $user = auth('vendor')->user();
       
        $store =Store::where('vendor_id',$user->id)->first();
        if($store)
        {
            $user->store ='true';
            //$user->save();
        }
        else
        {
            $user->store ='false';
            //$user->save();

        }
        return $this->createNewToken($token);

    }

    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:2,100|unique:vendors,username,NULL,id,deleted_at,NULL',
            'email' => 'required|string|email|max:100|unique:vendors,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6',
            'phone' =>'required|unique:vendors,phone,NULL,id,deleted_at,NULL',

        ]);


        if($validator->fails()){
            
            return response()->json(
                ['status'=>false,
                'message'=>$validator->errors(),
                'code'=>400],400);

        }

        $customer = User::where('email',$request->email)->where('deleted_at','NULL')->where('in_block','NULL')->first();
        if($customer)
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('app.vendorExist'),
                'code'=>400,
            ],400);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:2,100|unique:users,username,NULL,id,deleted_at,NULL',
            'email' => 'required|string|email|max:100|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6',
            'phone' =>'required|unique:users,phone,NULL,id,deleted_at,NULL',

        ]);


        if($validator->fails())
        {
            
            return response()->json(
                ['status'=>false,
                'message'=>$validator->errors(),
                'code'=>400],400);

        }

        $vendor = new Vendor();
        $vendor->username = $request->username;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = bcrypt($request->password);
        $vendor->last_login=Carbon::now();
        $vendor->approved=1;
        $vendor->save();


        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->uuid = Helper::IDGenerator('users', 'uuid', 4, 'C');
        $user->password = bcrypt($request->password);
        $user->save();


        $token = auth('vendor')->attempt($validator->validated());

        try {     
            $code = mt_rand(1000, 9999);
          
            //User::where('email', $user->email )->update(['codeActive' => $code]);

            $myemail = $vendor->email ;
            $vendor->activation_code = $code;
            $vendor->save();

            /*Mail::send([], [], function ($message) use ($myemail,$code) {
                $message->to($myemail)
                ->subject('Account activation code')
                ->from('info@trkar.com')
                ->setBody("<h1>The account activation code has been sent</h1><font color='red'> $code </font>", 'text/html');
                });*/

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

    public function isValidToken(Request $request)
    {
        
            return response()->json([
                'status'=>true,
                'message'=>trans('app.valid'),
                'code'=>200,
                'data' => auth('vendor')->check()           
            ],200); 

    }

    public function userProfile()
    {
        return response()->json([
            'status'=>true,
            'message'=>trans('app.userProfile'),
            'code'=>200,
            'data' => auth('vendor')->user()           
        ],200);
    }

    public function refresh() 
    {
        return $this->createNewToken(auth('vendor')->refresh());
    
    }

    public function logout() 
    {
        auth('vendor')->logout();
        return response()->json(['status'=>true,'message'=>trans('app.logout_success'),'code'=>200],200);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'status'=>true,
            'message'=>trans('app.token_success'),
            'code'=>201,
            'access_token' => $token,
            'token_type' => 'bearer',
            'data' => auth('vendor')->user()
        ],201);
    }

    public function resend($email)
    {
        $user=Vendor::where('email',$email)->first();
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

    public function verifiy($code, $email)
    {
        $user = Vendor::where('email',$email)->first();
        $user1 = User::where('email',$email)->first();

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
                $user1->email_verified_at =Carbon::now();

                $user->save();
                $user1->save();

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
    
    public function send_mail()
    {
        $code = mt_rand(1000, 9999);
          
        //User::where('email', $user->email )->update(['codeActive' => $code]);

        $myemail = 'noortaher33@gmail.com' ;
        
        $data = array('code'=>$code);

        Mail::send(['html'=>'ActivationCodeTemplete'], $data, function ($message) use ($myemail,$code) {
            $message->to($myemail)
            ->subject('Account activation code')
            ->from('info@trkar.com');
            //->setBody();
            });
    }
}
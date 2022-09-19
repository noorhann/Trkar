<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManagerStatic as Image;

class SystemController extends Controller{

    protected $viewData = [
        'breadcrumb'=> []
    ];

    public function __construct(){
        //$this->middleware(['auth:staff']);
    }

    protected function view($file,array $data = []){
        return view('system.'.$file,$data);
    }

    protected function response($status,$code = 200,$message = null,$data = []): array {
        return [
            'status'=> $status,
            'code'=> $code,
            'message'=> $message,
            'data'=> $data
        ];
    }


    protected function validator(Array $request,Array $rules){
        $validator = Validator::make($request,$rules);
        if ($validator->fails()) {
            return $this->response(false,330004,'Validation Error',[
                'middleware_validation_error'=> $validator->errors()->toArray()
            ]);
        }
        return true;
    }
}

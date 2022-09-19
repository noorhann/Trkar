<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Audit_Log;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    public function index()
    {
        $log=Audit_Log::get();
        return response()->json(
            ['status'=>true,
            'message'=>trans('app.logs'),
            'code'=>200,
            'data'=>$log,
        ],200);
    }
}

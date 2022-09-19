<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Season;

class SeasonController extends Controller
{
    public function all()
    {
        $season = Season::get();
        return response()->json([
            'status'=>true,
            'message'=>trans('Season have been shown successfully'),
            'code'=>200,
            'data'=>$season,
        ],200);
    }
}


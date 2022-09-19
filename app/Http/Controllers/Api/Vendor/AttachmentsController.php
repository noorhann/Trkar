<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Store;
use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StoreRejectStatus;
use Illuminate\Support\Facades\Storage;

class AttachmentsController extends Controller
{
    public function create(Request $request)
    {
        $bank_account = new Attachment();
        if( $request->file('bank_account'))
        {
          
            $bank_account->file =Storage::disk('public')->put("attachments",  $request->file('bank_account'));

        }
        $bank_account->mime_type = $request->file('bank_account')->getMimeType();
        $bank_account->size = $request->file('bank_account')->getSize();
        $bank_account->user_id =auth('vendor')->user()->id;
        $bank_account->file_name=$request->file('bank_account')->getClientOriginalName();
        $bank_account->type = 1 ;
        $bank_account->save();

        $commercial_number = new Attachment();
        if($request->file('commercial_number'))
        {
          
            $commercial_number->file =Storage::disk('public')->put("attachments",  $request->file('commercial_number'));

        }
        $commercial_number->mime_type = $request->file('commercial_number')->getMimeType();
        $commercial_number->size = $request->file('commercial_number')->getSize();
        $commercial_number->user_id =auth('vendor')->user()->id;
        $commercial_number->file_name=$request->file('commercial_number')->getClientOriginalName();
        $commercial_number->type = 2 ;
        $commercial_number->save();

        $tax_number = new Attachment();
        if( $request->file('tax_number'))
        {
          
            $tax_number->file =Storage::disk('public')->put("attachments",  $request->file('tax_number'));

        }
        $tax_number->mime_type = $request->file('tax_number')->getMimeType();
        $tax_number->size = $request->file('tax_number')->getSize();
        $tax_number->user_id =auth('vendor')->user()->id;
        $tax_number->file_name=$request->file('tax_number')->getClientOriginalName();
        $tax_number->type = 3;
        $tax_number->save();

        
        return response()->json([
            'status'=>true,
            'message'=>trans('attachment has been uploaded successfully'),
            'code'=>200,
            'bank_account'=>$bank_account,
            'commercial_number'=>$commercial_number,
            'tax_number'=>$tax_number,

        ],200);

    }

    public function get()
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }

        $store =Store::where('vendor_id',auth('vendor')->user()->id)->first();
        $reject_bank = StoreRejectStatus::where('reject_status_id',3)->where('store_id',$store->id)->first();
        if($reject_bank->status == 1)
        {
            $att =Attachment::where('user_id',auth('vendor')->user()->id)->where('type',1)->first();
            $att->approved = 1 ;
            $att->save();
        }

        $reject_commercial = StoreRejectStatus::where('reject_status_id',1)->where('store_id',$store->id)->first();
        if($reject_commercial->status == 1)
        {
            $att =Attachment::where('user_id',auth('vendor')->user()->id)->where('type',2)->first();
            $att->approved = 1 ;
            $att->save();
        }

        $reject_tax = StoreRejectStatus::where('reject_status_id',2)->where('store_id',$store->id)->first();
        if($reject_tax->status == 1)
        {
            $att =Attachment::where('user_id',auth('vendor')->user()->id)->where('type',3)->first();
            $att->approved = 1 ;
            $att->save();
        }
        
        $att = Attachment::where('user_id',auth('vendor')->user()->id)->get();

        return response()->json([
            'status'=>true,
            'message'=>trans('attachment has been shown successfully'),
            'code'=>200,
            'data'=>$att,
        ],200);

    }
}

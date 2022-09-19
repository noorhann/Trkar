<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Store;
use App\Models\Vendor;
use App\Helpers\Helper;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\StoreRejectStatus;
use App\Models\VendorUuid;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|between:2,100|unique:stores,name_ar,NULL,id,deleted_at,NULL',
            'name_en' => 'required|string|between:2,100|unique:stores,name_en,NULL,id,deleted_at,NULL',
            'email' => 'required|string|email|max:100|unique:stores,email,NULL,id,deleted_at,NULL',
            'phone' =>'required|integer|digits_between:9,9|unique:stores,phone,NULL,id,deleted_at,NULL',
            'store_type_id' =>'required',
            'country'=>'required',
            'city'=>'required',
            'area'=>'required',
            'bank_account'=>'required',
            'commercial_number'=>'required',
            'tax_card_number'=>'required',
            'address'=>'required|string',
            'description_ar'=>'nullable|string',
            'description_en'=>'nullable|string',

            'longitude'=>'required',
            'latitude'=>'required',
            
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

        $store= new Store();
        $store->name_ar=$request->name_ar;
        $store->name_en=$request->name_en;
        $store->uuid=Helper::IDGenerator('stores', 'uuid', 4, 'S');
        $store->vendor_id=auth('vendor')->user()->id;
        $store->store_type_id=$request->store_type_id;
        $store->description_ar=$request->description_ar;
        $store->description_en=$request->description_en;
        //$uploadFolder = 'stores';
        if($image = $request->file('image'))
        {
            //$image_uploaded_path = $image->store($uploadFolder, 'public');
         
            $store->image =Storage::disk('public')->put("stores",  $request->file('image'));
        }
        if($banner = $request->file('banner'))
        {
            //$image_uploaded_path = $banner->store($uploadFolder, 'public');
            $store->banner =Storage::disk('public')->put("stores",  $request->file('banner'));
        }
        $store->phone=$request->phone;
        $store->email=$request->email;
        $store->status=0;
        $store->address = $request->address;
        $store->longitude = $request->longitude;
        $store->latitude = $request->latitude;
        $store->country = $request->country;
        $store->area = $request->area;
        $store->city = $request->city;
        $store->save();

        $uuid = new VendorUuid();

        if($store->store_type_id == 1)
        {
            $uuid->retail = Helper::IDGenerator('vendor_uuids', 'retail', 4, 'VR');

        }
        if($store->store_type_id == 2)
        {
            $uuid->wholesale = Helper::IDGenerator('vendor_uuids', 'wholesale', 4, 'VW');

        }
        if($store->store_type_id == 3)
        {
            $uuid->retail = Helper::IDGenerator('vendor_uuids', 'retail', 4, 'VR');
            $uuid->wholesale = Helper::IDGenerator('vendor_uuids', 'wholesale', 4, 'VW');

        }

        $uuid->save();
        $vendor=Vendor::find(auth('vendor')->id());
        $vendor->vendor_uuids_id = $uuid->id;
        //$vendor->country_id= $request->input('country_id');
        //$vendor->area_id= $request->input('area_id');
       // $vendor->city_id= $request->input('city_id');
        $vendor->bank_account= $request->input('bank_account');
        $vendor->commercial_number= $request->input('commercial_number');
        $vendor->tax_card_number= $request->input('tax_card_number');
        $vendor->save();
       
        
        $reject = new StoreRejectStatus();
        $reject->store_id = $store->id;
        $reject->reject_status_id = '1';
        $reject->status = 0;
        $reject->save();

        $reject = new StoreRejectStatus();
        $reject->store_id = $store->id;
        $reject->reject_status_id = '2';
        $reject->status = 0;
        $reject->save();

        $reject = new StoreRejectStatus();
        $reject->store_id = $store->id;
        $reject->reject_status_id = '3';
        $reject->status = 0;
        $reject->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.store_create'),
            'code'=>200,
            'data'=>$store
        ],200);

    }

    public function update(Request $request , $id)
    {
        if(!auth()->guard('vendor')->check())
        {
            return response()->json(['status'=>false,
            'message'=>trans('app.validation_error'),
            'code'=>401],
            401);
        }

        $store = Store::where('id',$id)->first();

        if(!$store)
        {
            return response()->json([
                'status'=>false,
                'message'=>trans('No store found'),
                'code'=>404,
            ],404);
        }

    
        if($request->has('description_ar'))
        {
            $store->description_ar=$request->input('description_ar');
        }
        if($request->has('description_en'))
        {
            $store->description_en=$request->input('description_en');
        }
        if($request->has('address'))
        {
            $store->address=$request->input('address');
        }
        if($request->has('longitude'))
        {
            $store->longitude=$request->input('longitude');
        }
        if($request->has('latitude'))
        {
            $store->latitude=$request->input('latitude');
        }
        if($request->has('image'))
        {
            $store->image =Storage::disk('public')->put("stores",  $request->file('image'));

        }  
        if($request->has('banner'))
        {
            $store->banner =Storage::disk('public')->put("stores",  $request->file('banner'));

        }             
        
        $bank_account = Attachment::where('user_id',auth('vendor')->user()->id)->where('type',1)->first();
        if($request->has('bank_account'))
        { 
            $bank_account->file = Storage::disk('public')->put("attachments",  $request->file('bank_account'));
            $bank_account->mime_type = $request->file('bank_account')->getMimeType();
            $bank_account->size = $request->file('bank_account')->getSize();
            $bank_account->user_id =auth('vendor')->user()->id;
            $bank_account->file_name=$request->file('bank_account')->getClientOriginalName();
            $bank_account->save();
        }

        $commercial_number = Attachment::where('user_id',auth('vendor')->user()->id)->where('type',2)->first();
        if($request->has('commercial_number'))
        { 

            $commercial_number->file = Storage::disk('public')->put("attachments",  $request->file('commercial_number'));
            $commercial_number->mime_type = $request->file('commercial_number')->getMimeType();
            $commercial_number->size = $request->file('commercial_number')->getSize();
            $commercial_number->user_id =auth('vendor')->user()->id;
            $commercial_number->file_name=$request->file('commercial_number')->getClientOriginalName();
            $commercial_number->save();

        }

        $tax_number = Attachment::where('user_id',auth('vendor')->user()->id)->where('type',3)->first();
        if($request->has('tax_number'))
        { 
            $tax_number->file = Storage::disk('public')->put("attachments",  $request->file('tax_number'));
            $tax_number->mime_type = $request->file('tax_number')->getMimeType();
            $tax_number->size = $request->file('tax_number')->getSize();
            $tax_number->user_id =auth('vendor')->user()->id;
            $tax_number->file_name=$request->file('tax_number')->getClientOriginalName();
            $tax_number->save();
        }


        $vendor = Vendor::where('id',auth('vendor')->user()->id)->first();
        if($request->has('bank_account_number'))
        { 
            $vendor->bank_account = $request->input('bank_account_number');
        }
        if($request->has('commercial_number_text'))
        { 
            $vendor->commercial_number = $request->input('commercial_number_text');
        }
        if($request->has('tax_card_number'))
        { 
            $vendor->tax_card_number = $request->input('tax_card_number');
        }
        $store->save();
        $vendor->save();

        return response()->json([
            'status'=>true,
            'message'=>trans('app.storeUpdate'),
            'code'=>200,
            'data'=>$store,
            'bank_account'=>$bank_account,
            'commercial_number'=>$commercial_number,
            'tax_number'=>$tax_number,
        ],200);
    }
    public function create_branch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',  
            'address' =>'required|string',
            'phone'=>'required|integer',
            'longitude'=>'required',
            'latitude'=>'required', 
            'branch_picked_address'  =>'required|string',     
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,
                                    'message'=>$validator->errors(),
                                    'code'=>400],400);
        }

        $branch= new StoreBranch();
        $branch->name=$request->name;
        $branch->address=$request->address;
        $branch->phone=$request->phone;
        $branch->longitude=$request->longitude;
        $branch->latitude=$request->latitude;
        $branch->branch_picked_address=$request->branch_picked_address;

        $branch->slug=Str::slug($request->get('name'));
        $branch->status=0;
        $branch->store_id =$request->store_id; 

        $branch->save();
        return response()->json([
            'status'=>true,
            'message'=>trans('app.store_create'),
            'code'=>200,
            'data'=>$branch
        ],200);
    }

    public function generate_url($name)
    {
        $store = Store::where('name_en',$name)->first();
        $url=url("/store/{$store->name_en}");
        return response()->json([
            'status'=>true,
            'message'=>trans('url created successfully'),
            'code'=>200,
            'data'=>$url,
        ],200);

    }

    public function get_vendor_store()
    {
        $store = Store::where('vendor_id',auth('vendor')->user()->id)->orderby('id','desc')->first();
        $branchs = StoreBranch::where('store_id',$store->id)->get();
        return response()->json([
            'status'=>true,
            'message'=>trans('products have been shown successfully'),
            'code'=>200,
            'data'=>$store,
            'branch'=>$branchs
        ],200);
        
    }
}

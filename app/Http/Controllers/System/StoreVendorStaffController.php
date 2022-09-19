<?php

namespace App\Http\Controllers\System;

use App;
use App\Helpers\Helper;
use App\Http\Requests\StoreVendorStaffFormRequest;
use App\Models\StoreVendorStaff;

class StoreVendorStaffController extends SystemController
{
    public function index(StoreVendorStaffFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  StoreVendorStaff::select([
                'id',
                'vendor_staff_id',
                'store_id',
                'approved',
                'created_at'
            ]);
            if ($request->created_at1 && $request->created_at2) {
                $eloquentData->whereBetween('created_at', [
                    $request->created_at1 . ' 00:00:00',
                    $request->created_at2 . ' 23:59:59'
                ]);
            }

            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->orWhereHas('vendorStaff', function ($q) use ($request) {
                        $q->where('username', 'LIKE', '%' . $request->name . '%');
                    })
                        ->orWhereHas('store', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    
                    __('Vendor Staff'),
                    __('Store'),
                    __('Approved'),
                    __('Action')

                ])
                ->addColumn('vendorStaff', function ($data) {
                    if (isset($data->vendorStaff)) {
                        $name = '<div class="text-left"><span>' . __('Name') . ' : </span><span>' . $data->vendorStaff->username . '</span><br>';
                        $email = '<span>' . __('Email') . ' : </span><span>' . $data->vendorStaff->email . '</span><br>';
                        $phone = '<span>' . __('Phone') . ' : </span><span>' . $data->vendorStaff->phone . '</span></br>';
                        $vendor = '<span>' . __('Vendor Name') . ' : </span><span>' . $data->vendorStaff->vendor->username . '</span></div>';
    
                        return $name . $email . $phone . $vendor;
                    } else {
                        return '--';
                    }
            
                })

                ->addColumn('store', function ($data) {
                    if (isset($data->store)) {
                        return $data->store->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
               

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.store-vendor-staff.approved')) {
                        if ($data->approved == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.store-vendor-staff.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->approved == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.store-vendor-staff.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {
                    $delete = '';
                    if (Helper::adminCan('admin.store-vendor-staff.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.store-vendor-staff.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return  $delete;
                   
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Store Vendor Staff')
            ];

            $this->viewData['pageTitle'] = __('Store Vendor Staff');

            return $this->view('store_vendor_staff.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store Vendor Staff'),
            'url' => route('admin.store-vendor-staff.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Add Store Vendor Staff'),
        ];

        $this->viewData['pageTitle'] = __('Add Store Vendor Staff');

        return $this->view('store_vendor_staff.create', $this->viewData);
    }

    public function store(StoreVendorStaffFormRequest $request)
    {
        $requestData = $request->all();
     
        $insertData = StoreVendorStaff::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.store-vendor-staff.create')
                ]
            );
        } else {
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to add data')
            );
        }
    }
    public function edit(StoreVendorStaff $storeVendorStaff)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store Vendor Staff'),
            'url' => route('admin.store-vendor-staff.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $storeVendorStaff->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Store Vendor Staff');
        $this->viewData['result'] = $storeVendorStaff;

        return $this->view('store_vendor_staff.create', $this->viewData);
    }


    public function update(StoreVendorStaffFormRequest $request, StoreVendorStaff $storeVendorStaff)
    {
        $requestData = $request->all();
       

        $updateData = $storeVendorStaff->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.store-vendor-staff.index')
                ]
            );
        } else {
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to modify the data')
            );
        }
    }

    public function show(StoreVendorStaff $storeVendorStaff)
    {
      abort(404);
    }
    public function destroy(StoreVendorStaff $storeVendorStaff)
    {
        $storeVendorStaff->forceDelete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.store-vendor-staff.index')
            ]
        );
    }
    public function approvedStatus(StoreVendorStaff $storevendorstaff)
    {
        if ($storevendorstaff->approved == 0) {
            $approved = 1;
        } elseif ($storevendorstaff->approved == 1) {
            $approved = 0;
        }
        $updateData = $storevendorstaff->update([
            'approved' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.store-vendor-staff.index')
            ]
        );
    }
}

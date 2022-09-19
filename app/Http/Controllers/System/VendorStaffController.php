<?php

namespace App\Http\Controllers\System;


use Illuminate\Support\Facades\Storage;

use App;
use App\Helpers\Helper;
use App\Http\Requests\VendorStaffFormRequest;
use App\Models\VendorStaff;

class VendorStaffController extends SystemController
{
    public function index(VendorStaffFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  VendorStaff::select([
                'id',
                'uuid',
                'username',
                'email',
                'phone',
                'vendor_id',
                'status',
                'create_by'
            ]);
            if ($request->created_at1 && $request->created_at2) {
                $eloquentData->whereBetween('created_at', [
                    $request->created_at1 . ' 00:00:00',
                    $request->created_at2 . ' 23:59:59'
                ]);
            }

            if ($request->status == 2) {
                $status = 0;
                $eloquentData->where('status', $status);
            } elseif ($request->status == 1) {
                $eloquentData->where('status', $request->status);
            }
            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->where('uuid', 'LIKE', '%' . $request->name . '%')
                        ->where('username', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('email', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('phone', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('vendor', function ($q) use ($request) {
                            $q->where('username', 'LIKE', '%' . $request->name . '%');
                        })
                        ->orWhereHas('create_by', function ($q) use ($request) {
                            $q->where('username', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([

                    __('uuid'),
                    __('User Name'),
                    __('Email'),
                    __('Phone'),
                    __('Vendor'),
                    __('Created By'),
                    __('Status'),
                    __('Action')
                ])

                ->addColumn('uuid')
                ->addColumn('username')
                ->addColumn('email')
                ->addColumn('phone')
                ->addColumn('vendor', function ($data) {
                    if (isset($data->vendor)) {
                        return $data->vendor->username;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('create_by', function ($data) {
                    if (isset($data->user)) {
                        return $data->user->username;
                    } else {
                        return '--';
                    }
                })

                ->addColumn('status', function ($data) {
                    if (Helper::adminCan('admin.vendor-staff.approvedVendorStaff')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange=" approvedVendorStaff(\'' . route('admin.vendor-staff.approvedVendorStaff', $data->id) . '\');"  type="checkbox" class="custom-control-input" id=" approvedVendorStaff' . $data->id . '">
                        <label class="custom-control-label" for=" approvedVendorStaff' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input onchange=" approvedVendorStaff(\'' . route('admin.vendor-staff.approvedVendorStaff', $data->id) . '\');"  type="checkbox" class="custom-control-input" id=" approvedVendorStaff' . $data->id . '">
                        <label class="custom-control-label" for=" approvedVendorStaff' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {
                    $delete = '';
                    if (Helper::adminCan('admin.vendor-staff.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.vendor-staff.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $delete;

                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Vendor Staff')
            ];

            $this->viewData['pageTitle'] = __('Vendor Staff');

            return $this->view('vendor_staff.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Vendor Staff'),
            'url' => route('admin.vendor-staff.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Add Vendor Staff'),
        ];

        $this->viewData['pageTitle'] = __('Add Vendor Staff');

        return $this->view('vendor_staff.create', $this->viewData);
    }

    public function store(VendorStaffFormRequest $request)
    {
        $requestData = $request->all();
        $requestData['password'] =  bcrypt($requestData['password']);
        $requestData['create_by'] =  \Auth::id();

        $requestData['uuid'] =  Helper::IDGenerator('vendor_staff', 'uuid', 4, 'C');

        $insertData = VendorStaff::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.vendor-staff.create')
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
    public function edit(VendorStaff $vendorStaff)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Vendor Staff'),
            'url' => route('admin.vendor-staff.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $vendorStaff->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Vendor Staff');
        $this->viewData['result'] = $vendorStaff;

        return $this->view('vendor_staff.create', $this->viewData);
    }


    public function update(VendorStaffFormRequest $request, VendorStaff $vendorStaff)
    {
        $requestData = $request->all();
        $requestData['password'] =  bcrypt($requestData['password']);
        $requestData['uuid'] =  Helper::IDGenerator('vendor_staff', 'uuid', 4, 'C');

        $updateData = $vendorStaff->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.vendor-staff.index')
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

    public function show(VendorStaff $vendorStaff)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Vendor Staff'),
            'url' => route('admin.vendor-staff.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $vendorStaff->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Show Vendor Staff');
        $this->viewData['result'] = $vendorStaff;

        return $this->view('vendor_staff.show', $this->viewData);
    }
    public function destroy(VendorStaff $vendorStaff)
    {
        $vendorStaff->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.vendor-staff.index')
            ]
        );
    }
    public function approvedVendorStaff(VendorStaff $vendorStaff)
    {
        if ($vendorStaff->status == 0) {
            $status = 1;
        } elseif ($vendorStaff->status == 1) {
            $status = 0;
        }
        $updateData = $vendorStaff->update([
            'status' => $status
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.vendor-staff.index')
            ]
        );
    }
}

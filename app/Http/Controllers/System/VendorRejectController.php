<?php

namespace App\Http\Controllers\System;

use App\Models\VendorReject;

use Illuminate\Support\Facades\Storage;

use App;
use App\Helpers\Helper;
use App\Http\Requests\VendorRejectFormRequest;

class VendorRejectController extends SystemController
{
    public function index(VendorRejectFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  VendorReject::select([
                'id',
                'vendor_id',
                'reject_status_id',
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
                    $query->orWhereHas('vendor', function ($q) use ($request) {
                        $q->where('username', 'LIKE', '%' . $request->name . '%');
                    })
                        ->orWhereHas('rejectStatus', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    
                    __('Vendor'),
                    __('Reject Status'),
                    __('Action')

                ])
                
                ->addColumn('vendor', function ($data) {
                    if (isset($data->vendor)) {
                        return $data->vendor->username;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('rejectStatus', function ($data) {
                    if (isset($data->rejectStatus)) {
                        return $data->rejectStatus->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })



                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.vendor-reject.edit')) {
                        $edit = '<a href="' . route('admin.vendor-reject.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.vendor-reject.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.vendor-reject.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
                   
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Vendor Reject')
            ];

            $this->viewData['pageTitle'] = __('Vendor Reject');

            return $this->view('vendor_reject.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Vendor Reject'),
            'url' => route('admin.vendor-reject.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Add Vendor Reject'),
        ];

        $this->viewData['pageTitle'] = __('Add Vendor Reject');

        return $this->view('vendor_reject.create', $this->viewData);
    }

    public function store(VendorRejectFormRequest $request)
    {
        $requestData = $request->all();
     
        $insertData = VendorReject::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.vendor-reject.create')
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
    public function edit(VendorReject $vendorReject)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Vendor Reject'),
            'url' => route('admin.vendor-reject.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $vendorReject->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Vendor Reject');
        $this->viewData['result'] = $vendorReject;

        return $this->view('vendor_reject.create', $this->viewData);
    }


    public function update(VendorRejectFormRequest $request, VendorReject $vendorReject)
    {
        $requestData = $request->all();
       

        $updateData = $vendorReject->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.vendor-reject.index')
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

    public function show(VendorReject $vendorReject)
    {
      abort(404);
    }
    public function destroy(VendorReject $vendorReject)
    {
        $vendorReject->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.vendor-reject.index')
            ]
        );
    }
}

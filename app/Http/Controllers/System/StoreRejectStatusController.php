<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\StoreRejectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\StoreRejectStatusFormRequest;
use App\Models\Store;

class StoreRejectStatusController extends SystemController
{
    public function index(StoreRejectStatusFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  StoreRejectStatus::select([
                'id',
                'reject_status_id',
                'store_id',
                'status',
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
                    $query->WhereHas('rejectStatus', function ($q) use ($request) {
                        $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                    })->orWhereHas('store', function ($q) use ($request) {
                        $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                    });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([

                    __('Reject Status'),
                    __('Store'),
                    __('Status'),
                    // __('Action')

                ])


                ->addColumn('reject_status_id', function ($data) {
                    if (isset($data->rejectStatus)) {
                        return $data->rejectStatus->{'name_' . App::getLocale()};
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
                    if (Helper::adminCan('admin.store-reject-status.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.store-reject-status.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.store-reject-status.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                // ->addColumn('action', function ($data) {
                //     $edit = '';
                //     if (Helper::adminCan('admin.store-reject-status.edit')) {
                //         $edit = '<a href="' . route('admin.store-reject-status.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                //     }

                //     return $edit;
                // })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Stores are awaiting approval')
            ];

            $this->viewData['pageTitle'] = __('Stores are awaiting approval');

            return $this->view('store_reject_status.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Stores are awaiting approval'),
            'url' => route('admin.store-reject-status.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Stores are awaiting approval'),
        ];

        $this->viewData['pageTitle'] = __('Create Stores are awaiting approval');

        return $this->view('store_reject_status.create', $this->viewData);
    }

    public function store(StoreRejectStatusFormRequest $request)
    {

        $requestData = $request->all();
        $requestData['status'] = 1;

        $insertData = StoreRejectStatus::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.store-reject-status.create')
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
    public function edit(StoreRejectStatus $storeRejectStatus)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Stores are awaiting approval'),
            'url' => route('admin.store-reject-status.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $storeRejectStatus->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Stores are awaiting approval');
        $this->viewData['result'] = $storeRejectStatus;

        return $this->view('store_reject_status.create', $this->viewData);
    }


    public function update(StoreRejectStatusFormRequest $request, StoreRejectStatus $storeRejectStatus)
    {

        $requestData = $request->all();
        $updateData = $storeRejectStatus->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.store-reject-status.index')
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

    public function show()
    {
        abort(404);
    }
    public function destroy(StoreRejectStatus $storeRejectStatus)
    {
        $storeRejectStatus->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.store-reject-status.index')
            ]
        );
    }
    public function approvedStatus(StoreRejectStatus $storeRejectStatus)
    {
        if ($storeRejectStatus->status == 0) {
            $status = 1;
        } elseif ($storeRejectStatus->status == 1) {
            $status = 0;
        }
        $updateData = $storeRejectStatus->update([
            'status' => $status
        ]);
        $storeRejectStatusGet = StoreRejectStatus::where('store_id',$storeRejectStatus['store_id'])->where('status',1)->get();
        if(count($storeRejectStatusGet) === 3){

            $store = Store::find($storeRejectStatus['store_id']);
         
            $updateData = $store->update([
                'approved' => 1
            ]);
        }
        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                
            ]
        );
    }
    public function approvedStoreRejectStatus(StoreRejectStatus $storeRejectStatus)
    {
        if ($storeRejectStatus->status == 0) {
            $approved = 1;
        } elseif ($storeRejectStatus->status == 1) {
            $approved = 0;
        }

        $updateData = $storeRejectStatus->update([
            'status' => $approved
        ]);
        $storeRejectStatusGet = StoreRejectStatus::where('store_id',$storeRejectStatus['store_id'])->where('status',1)->get();
        if(count($storeRejectStatusGet) === 3){

            $store = Store::find($storeRejectStatus['store_id']);
         
            $updateData = $store->update([
                'approved' => 1
            ]);
        }
        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                
            ]
        );
    }
}

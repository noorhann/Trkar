<?php

namespace App\Http\Controllers\System;

use App\Models\StoreBranch;

use Illuminate\Support\Facades\Storage;

use App;
use App\Helpers\Helper;
use App\Http\Requests\StoreBranchFormRequest;
use App\Models\ProductQuantity;
use Illuminate\Support\Str;


class StoreBranchController extends SystemController
{
    public function index(StoreBranchFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  StoreBranch::select([
                'id',
                'name',
                'slug',
                'store_id',
                'address',
                'phone',
                'status',
                'branch_picked_address',
                'created_at'
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
                    $query->where('name', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('address', 'LIKE', '%' . $request->name . '%')
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
                    __('Name'),
                    __('Phone'),
                    __('Address'),
                    __('Shipping Address'),
                    __('Store'),
                    __('Vendor'),
                    __('Status'),
                    __('Action')
                ])

                ->addColumn('name')
                ->addColumn('phone')
                ->addColumn('address')
                ->addColumn('branch_picked_address')

                ->addColumn('store', function ($data) {
                    if (isset($data->store)) {
                        return $data->store->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('vendor', function ($data) {
                    if (isset($data->store->vendor)) {
                        return $data->store->vendor->username;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.store-branch.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.store-branch.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.store-branch.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $show = '';
                    $delete = '';
                    if (Helper::adminCan('admin.store-branch.edit')) {
                        $edit = '<a href="' . route('admin.store-branch.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.store-branch.show')) {
                        $show = ' <a href="' . route('admin.store-branch.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.store-branch.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.store-branch.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $show . $delete;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Store Branch')
            ];

            $this->viewData['pageTitle'] = __('Store Branch');

            return $this->view('store_branch.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store Branch'),
            'url' => route('admin.store-branch.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Add Store Branch'),
        ];

        $this->viewData['pageTitle'] = __('Add Store Branch');

        return $this->view('store_branch.create', $this->viewData);
    }

    public function store(StoreBranchFormRequest $request)
    {
        $requestData = $request->all();
        $requestData['slug'] = Str::slug($request->get('name')) . ' - ' . Helper::quickRandom();

        $insertData = StoreBranch::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.store-branch.create')
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
    public function edit(StoreBranch $storeBranch)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store'),
            'url' => route('admin.store-branch.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $storeBranch->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Store');
        $this->viewData['result'] = $storeBranch;

        return $this->view('store_branch.create', $this->viewData);
    }


    public function update(StoreBranchFormRequest $request, StoreBranch $storeBranch)
    {
        $requestData = $request->all();

        $requestData['slug'] = Str::slug($request->get('name')) . ' - ' . Helper::quickRandom();
        unset($requestData['phone']);
        unset($requestData['branch_picked_address']);
        $updateData = $storeBranch->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.store-branch.index')
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

    public function show(StoreBranch $storeBranch)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store'),
            'url' => route('admin.store-branch.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $storeBranch->{'name_' . App::getLocale()}]),
        ];
        $productQuantities = ProductQuantity::where('branch_id', $storeBranch->id)->get();

        $this->viewData['pageTitle'] = __('Show Products of this Branch');
        $this->viewData['result'] = $storeBranch;
        $this->viewData['products'] = $productQuantities;

        return $this->view('store_branch.show', $this->viewData);
    }
    public function destroy(StoreBranch $storeBranch)
    {
        $storeBranch->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.store-branch.index')
            ]
        );
    }
    public function approvedStatus(StoreBranch $storeBranch)
    {
        if ($storeBranch->status == 0) {
            $approved = 1;
        } elseif ($storeBranch->status == 1) {
            $approved = 0;
        }
        $updateData = $storeBranch->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.store-branch.index')
            ]
        );
    }
}

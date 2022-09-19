<?php

namespace App\Http\Controllers\System;

use App\Models\Store;

use Illuminate\Support\Facades\Storage;

use App;
use App\Helpers\Helper;
use App\Http\Requests\StoreFormRequest;
use App\Models\StoreBranch;

class StoreController extends SystemController
{
    public function index(StoreFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Store::select([
                'id',
                'uuid',
                'name_ar',
                'name_en',
                'email',
                'phone',
                'vendor_id',
                'store_type_id',
                'status',
                'image',
                'approved',
                'address',
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
            // dd($request->name);
            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->where('uuid', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('name_ar', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('email', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('phone', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('vendor', function ($q) use ($request) {
                            $q->where('username', 'LIKE', '%' . $request->name . '%');
                        })
                        ->orWhereHas('storeType', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([

                    __('uuid'),
                    __('Logo'),
                    __('Store Details'),
                    __('Vendor'),
                    __('Store Type'),
                    __('Count Branches'),
                    __('Status'),
                    __('Approved'),
                    __('Action')

                ])

                ->addColumn('uuid')
                ->addColumn('image', function ($data) {
                    if ($data->image == null) {
                        return '--';
                    }

                    return '<img src="' . Helper::path() . '/' .  $data->image . '" style="width: 40px; height: 40px;" />';
                })
                ->addColumn('storeDetails', function ($data) {
                    $name = '<div class="text-left"><span>' . __('Name') . ' : </span><span>' . $data->{'name_' . App::getLocale()} . '</span><br>';
                    $email = '<span>' . __('Email') . ' : </span><span>' . $data->email . '</span><br>';
                    $phone = '<span>' . __('Phone') . ' : </span><span>' . $data->phone . '</span></br>';
                    $address = '<span>' . __('Address') . ' : </span><span>' . $data->address . '</span></div>';

                    return $name . $email . $phone . $address;
                })

                ->addColumn('vendor', function ($data) {
                    if (isset($data->vendor)) {
                        return $data->vendor->username;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('storeType', function ($data) {
                    if (isset($data->storeType)) {
                        if ($data->storeType->id == 1) {
                            return '<span class="badge badge-soft-primary">' . $data->storeType->{'name_' . App::getLocale()} . '</span>';
                        } elseif ($data->storeType->id == 2) {
                            return '<span class="badge badge-soft-info">' . $data->storeType->{'name_' . App::getLocale()} . '</span>';
                        } elseif ($data->storeType->id == 3) {
                            return '<span class="badge badge-soft-warning">' . $data->storeType->{'name_' . App::getLocale()} . '</span>';
                        }
                    } else {
                        return '--';
                    }
                })
                ->addColumn('branches', function ($data) {
                    if (isset($data->branches)) {
                        return count($data->branches);
                    } else {
                        return '--';
                    }
                })
                ->addColumn('status', function ($data) {
                    if (Helper::adminCan('admin.store.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.store.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.store.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('approved', function ($data) {
                    if ($data->approved == 1) {
                        return '<span class="badge badge-soft-primary">' . __('Approved') . '</span>';
                    } elseif ($data->approved == 0) {
                        return '<span class="badge badge-soft-info">' . __('Not Approved') . '</span>';
                    }
                })

                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    $show = '';
                    if (Helper::adminCan('admin.store.edit')) {
                        $edit = '<a href="' . route('admin.store.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.store.show')) {
                        $show = ' <a href="' . route('admin.store.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.store.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.store.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete . $show;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Store')
            ];

            $this->viewData['pageTitle'] = __('Store');

            return $this->view('store.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store'),
            'url' => route('admin.store.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Add Store'),
        ];

        $this->viewData['pageTitle'] = __('Add Store');

        return $this->view('store.create', $this->viewData);
    }

    public function store(StoreFormRequest $request)
    {
        $requestData = $request->all();
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("store",  $request->file('image'));
        }

        $requestData['uuid'] =  Helper::IDGenerator('stores', 'uuid', 4, 'S');

        $insertData = Store::create($requestData);
        if ($insertData) {
            $branches = StoreBranch::where('store_id', $insertData->id)->get();
            if (count($branches)) {
                foreach ($branches as $key => $value) {
                    $value->update([
                        'status' => $insertData->status
                    ]);
                }
            }
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.store.create')
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
    public function edit(Store $store)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store'),
            'url' => route('admin.store.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $store->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Store');
        $this->viewData['result'] = $store;

        return $this->view('store.create', $this->viewData);
    }


    public function update(StoreFormRequest $request, Store $store)
    {
        $requestData = $request->all();
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("store",  $request->file('image'));
        } else {
            $requestData['image'] =  $store->image;
        }

        unset($requestData['email']);
        unset($requestData['phone']);
        $updateData = $store->update($requestData);
        if ($updateData) {
            $branches = StoreBranch::where('store_id', $store->id)->get();
            if (count($branches)) {
                foreach ($branches as $key => $value) {
                    $value->update([
                        'status' => $requestData['status']
                    ]);
                }
            }
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.store.index')
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

    public function show(Store $store)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store'),
            'url' => route('admin.store.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $store->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Details Store');
        $this->viewData['result'] = $store;

        return $this->view('store.show', $this->viewData);
    }
    public function destroy(Store $store)
    {
        $store->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.store.index')
            ]
        );
    }
    public function approved(StoreBranch $storeBranch)
    {
        if ($storeBranch->status == 0) {
            $status = 1;
        } elseif ($storeBranch->status == 1) {
            $status = 0;
        }
        $updateData = $storeBranch->update([
            'status' => $status
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.store.index')
            ]
        );
    }
    public function typePermission()
    {
    }
    public function approvedStatus(Store $store)
    {
        if ($store->status == 0) {
            $approved = 1;
        } elseif ($store->status == 1) {
            $approved = 0;
        }
        $updateData = $store->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.store.index')
            ]
        );
    }
}

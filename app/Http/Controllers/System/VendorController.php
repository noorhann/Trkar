<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\VendorFormRequest;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Store;
use App\Models\VendorUuid;
use App\Support\Collection;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class VendorController extends SystemController
{
    public function index(VendorFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Vendor::select([
                'id',
                'vendor_uuids_id',
                'username',
                'email',
                'phone',
                'country_id',
                'city_id',
                'area_id',
                'in_block',
                'approved',
                'created_at'
            ]);
            if ($request->created_at1 && $request->created_at2) {
                $eloquentData->whereBetween('created_at', [
                    $request->created_at1 . ' 00:00:00',
                    $request->created_at2 . ' 23:59:59'
                ]);
            }

            if ($request->approved == 2) {
                $approved = 0;
                $eloquentData->where('approved', $approved);
            } elseif ($request->approved) {
                $eloquentData->where('approved', $request->approved);
            }
            if ($request->status == 1) {
                $status = null;
                $eloquentData->where('in_block', $status);
            } elseif ($request->status == 2) {
                $eloquentData->where('in_block', '!=', null);
            }
            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->where('vendor_uuids_id', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('username', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('email', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('phone', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('country', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        })
                        ->orWhereHas('area', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        })
                        ->orWhereHas('city', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([

                    __('vendor uuids'),
                    __('Vendor Name'),
                    __('Email'),
                    __('Phone'),
                    __('Country'),
                    __('City'),
                    __('Area'),
                    __('Block'),
                    __('Status'),
                    __('Action')

                ])

                ->addColumn('vendor_uuids_id', function ($data) {
                    $uuid = VendorUuid::find($data->vendor_uuids_id);
                    if ($uuid) {
                        return __('retail') . ' : ' . $uuid->retail . '<br>' . __('wholesale') . ' : ' . $uuid->wholesale;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('username')
                ->addColumn('email')
                ->addColumn('phone')
                ->addColumn('country', function ($data) {
                    if (isset($data->country)) {
                        return $data->country->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('city', function ($data) {
                    if (isset($data->city)) {
                        return $data->city->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('area', function ($data) {
                    if (isset($data->area)) {
                        return $data->area->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('block', function ($data) {
                    if (Helper::adminCan('admin.vendor.approvedBlock')) {
                        if ($data->in_block == null) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedBlock(\'' . route('admin.vendor.approvedBlock', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="approvedBlock' . $data->id . '">
                        <label class="custom-control-label" for="approvedBlock' . $data->id . '"></label>';
                        } elseif ($data->in_block != null) {
                            return '<div class="custom-control custom-switch">
                        <input onchange="approvedBlock(\'' . route('admin.vendor.approvedBlock', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="approvedBlock' . $data->id . '">
                        <label class="custom-control-label" for="approvedBlock' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.vendor.approved')) {
                        if ($data->approved == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approved(\'' . route('admin.vendor.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->approved == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approved(\'' . route('admin.vendor.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })

                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    $show = '';
                    if (Helper::adminCan('admin.vendor.edit')) {
                        $edit = '<a href="' . route('admin.vendor.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.vendor.show')) {
                        $show = ' <a href="' . route('admin.vendor.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.vendor.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.vendor.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete . $show;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Vendor')
            ];

            $this->viewData['pageTitle'] = __('Vendor');

            return $this->view('vendor.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Vendor'),
            'url' => route('admin.vendor.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Add Vendor'),
        ];

        $this->viewData['pageTitle'] = __('Add Vendor');

        return $this->view('vendor.create', $this->viewData);
    }

    public function store(VendorFormRequest $request)
    {
        $requestData = $request->all();

        $requestData['vendor_uuids_id'] =  Helper::IDGenerator('vendors', 'vendor_uuids_id', 4, 'V');
        $requestData['password'] =  bcrypt($requestData['password']);
        if ($requestData['in_block'] == 1) {
            $requestData['in_block'] = null;
        } else {
            $requestData['in_block'] = Carbon::now();
        }
        $insertData = Vendor::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.vendor.create')
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
    public function edit(Vendor $vendor)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Vendor'),
            'url' => route('admin.vendor.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $vendor->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Vendor');
        $this->viewData['result'] = $vendor;

        return $this->view('vendor.create', $this->viewData);
    }


    public function update(VendorFormRequest $request, Vendor $vendor)
    {
        $requestData = $request->all();
        $requestData['vendor_uuids_id'] =  Helper::IDGenerator('vendors', 'vendor_uuids_id', 4, 'V');
        unset($requestData['email']);
        unset($requestData['phone']);

        if ($requestData['in_block'] == 1) {
            $requestData['in_block'] = null;
        } else {
            $requestData['in_block'] = Carbon::now();
        }
        $updateData = $vendor->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.vendor.index')
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

    public function show(Vendor $vendor)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Vendor'),
            'url' => route('admin.vendor.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $vendor->{'name_' . App::getLocale()}]),
        ];
        $store = Store::where('vendor_id', $vendor->id)->first();
        $this->viewData['pageTitle'] = __('Show Vendor');
        $this->viewData['result'] = $vendor;
        $this->viewData['store'] = $store;

        return $this->view('vendor.show', $this->viewData);
    }
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.vendor.index')
            ]
        );
    }
    public function approvedBlock(Vendor $vendor)
    {
        if ($vendor->in_block != null) {
            $approved = null;
        } elseif ($vendor->in_block == null) {
            $approved = Carbon::now();
        }
        $updateData = $vendor->update([
            'in_block' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.vendor.index')
            ]
        );
    }
    public function approved(Vendor $vendor)
    {
        if ($vendor->approved == 0) {
            $approved = 1;
        } elseif ($vendor->approved == 1) {
            $approved = 0;
        }
        $updateData = $vendor->update([
            'approved' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.vendor.index')
            ]
        );
    }
}

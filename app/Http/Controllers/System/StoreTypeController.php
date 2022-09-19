<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\StoreTypeFormRequest;
use App\Models\StoreType;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class StoreTypeController extends SystemController
{
    public function index(StoreTypeFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  StoreType::select([
                'id',
                'name_en',
                'name_ar',
                'status',
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
                    $query->where('name_ar', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Status'),
                    __('Action')

                ])
                
                ->addColumn('name_ar')
                ->addColumn('name_en')

                ->addColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return '<span class="badge badge-soft-success">' . __(' Active') . '</span>';
                    }

                    return '<span class="badge badge-soft-danger">' . __(' In-Active') . '</span>';
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.store-type.edit')) {
                        $edit = '<a href="' . route('admin.store-type.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.store-type.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.store-type.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
                    return '
                     <a href="' . route('admin.store-type.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                     <a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.store-type.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                            ';
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Store Types')
            ];

            $this->viewData['pageTitle'] = __('Store Types');

            return $this->view('store_type.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store Type'),
            'url' => route('admin.store-type.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Store Type'),
        ];

        $this->viewData['pageTitle'] = __('Create Store Type');

        return $this->view('store_type.create', $this->viewData);
    }

    public function store(StoreTypeFormRequest $request)
    {
       
        $requestData = $request->all();
        $requestData['status'] = 1;

        $insertData = StoreType::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.store-type.create')
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
    public function edit(StoreType $storeType)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Store Type'),
            'url' => route('admin.store-type.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $storeType->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Store Type');
        $this->viewData['result'] = $storeType;

        return $this->view('store_type.create', $this->viewData);
    }


    public function update(StoreTypeFormRequest $request, StoreType $storeType)
    {
      
        $requestData = $request->all();
        $updateData = $storeType->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.store-type.index')
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
    public function destroy(StoreType $storeType)
    {
        $storeType->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.store-type.index')
            ]
        );
    }
}

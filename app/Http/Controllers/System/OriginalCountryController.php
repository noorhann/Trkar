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
use App\Http\Requests\OriginalCountryFormRequest;
use App\Models\Category;
use App\Models\OriginalCountry;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class OriginalCountryController extends SystemController
{
    public function index(OriginalCountryFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  OriginalCountry::select([
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

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.original-country.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.original-country.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.original-country.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.original-country.edit')) {
                        $edit = '<a href="' . route('admin.original-country.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.original-country.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.original-country.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
               
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Original Countrys')
            ];

            $this->viewData['pageTitle'] = __('Original Countrys');

            return $this->view('original_country.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Original Country'),
            'url' => route('admin.original-country.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Original Country'),
        ];

        $this->viewData['pageTitle'] = __('Create Original Country');

        return $this->view('original_country.create', $this->viewData);
    }

    public function store(OriginalCountryFormRequest $request)
    {
       
        $requestData = $request->all();
        $requestData['status'] = 1;

        $insertData = OriginalCountry::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.original-country.create')
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
    public function edit(OriginalCountry $originalCountry)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Original Country'),
            'url' => route('admin.original-country.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $originalCountry->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Original Country');
        $this->viewData['result'] = $originalCountry;

        return $this->view('original_country.create', $this->viewData);
    }


    public function update(OriginalCountryFormRequest $request, OriginalCountry $originalCountry)
    {
      
        $requestData = $request->all();
        $updateData = $originalCountry->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.original-country.index')
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
    public function destroy(OriginalCountry $originalCountry)
    {
        $originalCountry->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.original-country.index')
            ]
        );
    }
    public function approvedStatus(OriginalCountry $originalCountry)
    {
        if ($originalCountry->status == 0) {
            $approved = 1;
        } elseif ($originalCountry->status == 1) {
            $approved = 0;
        }
        $updateData = $originalCountry->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.original-country.index')
            ]
        );
    }
}

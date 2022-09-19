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
use App\Http\Requests\CountryFormRequest;
use App\Models\Category;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class CountryController extends SystemController
{
    public function index(CountryFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Country::select([
                'id',
                'name_en',
                'name_ar',
                'country_code',
                'iso3',
                'numcode',
                'phonecode',
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
                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('country_code', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('iso3', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('phonecode', 'LIKE', '%' . $request->name . '%');
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Status'),
                    __('Country Code'),
                    __('iso3'),
                    __('Num Code'),
                    __('Phone Code'),
                    __('Action')

                ])
                ->addColumn('name_ar')
                ->addColumn('name_en')

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.country.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.country.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.country.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('country_code')
                ->addColumn('iso3')
                ->addColumn('numcode')
                ->addColumn('phonecode')
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.country.edit')) {
                        $edit = '<a href="' . route('admin.country.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.country.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.country.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Countrys')
            ];

            $this->viewData['pageTitle'] = __('Countrys');

            return $this->view('country.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Country'),
            'url' => route('admin.country.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Country'),
        ];

        $this->viewData['pageTitle'] = __('Create Country');

        return $this->view('country.create', $this->viewData);
    }

    public function store(CountryFormRequest $request)
    {

        $requestData = $request->all();
        $requestData['status'] = 1;

        $insertData = Country::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.country.create')
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
    public function edit(Country $country)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Country'),
            'url' => route('admin.country.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $country->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Country');
        $this->viewData['result'] = $country;

        return $this->view('country.create', $this->viewData);
    }


    public function update(CountryFormRequest $request, Country $country)
    {

        $requestData = $request->all();
        $updateData = $country->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.country.index')
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
    public function destroy(Country $country)
    {
        $country->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.country.index')
            ]
        );
    }
    public function approvedStatus(Country $country)
    {
        if ($country->status == 0) {
            $approved = 1;
        } elseif ($country->status == 1) {
            $approved = 0;
        }
        $updateData = $country->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.country.index')
            ]
        );
    }
}

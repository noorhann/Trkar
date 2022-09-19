<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App;
use App\Helpers\Helper;
use App\Http\Requests\CityFormRequest;


class CityController extends SystemController
{
    public function index(CityFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  City::select([
                'id',
                'country_id',
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
                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('country', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Country'),
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Status'),
                    __('Action')

                ])
                ->addColumn('country_id', function ($data) {
                    if (isset($data->country)) {
                        return $data->country->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('name_ar')
                ->addColumn('name_en')

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.city.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.city.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.city.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
               


                ->addColumn('action', function ($data) {
                    $edit = '';
                    if (Helper::adminCan('admin.city.edit')) {
                        $edit = '<a href="' . route('admin.city.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                
                    return $edit;
                  
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Cities')
            ];

            $this->viewData['pageTitle'] = __('Cities');

            return $this->view('city.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('City'),
            'url' => route('admin.city.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create City'),
        ];

        $this->viewData['pageTitle'] = __('Create City');

        return $this->view('city.create', $this->viewData);
    }

    public function store(CityFormRequest $request)
    {
    
        $requestData = $request->all();

        $insertData = City::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.city.create')
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
    public function edit(City $city)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('City'),
            'url' => route('admin.city.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $city->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit City');
        $this->viewData['result'] = $city;

        return $this->view('city.create', $this->viewData);
    }


    public function update(CityFormRequest $request, City $city)
    {
     
        $requestData = $request->all();

        $updateData = $city->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.city.index')
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
    public function destroy(City $city)
    {
        $city->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.city.index')
            ]
        );
    }
    public function approvedStatus(City $city)
    {
        if ($city->status == 0) {
            $approved = 1;
        } elseif ($city->status == 1) {
            $approved = 0;
        }
        $updateData = $city->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.city.index')
            ]
        );
    }
}

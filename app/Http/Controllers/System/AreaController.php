<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App;
use App\Helpers\Helper;
use App\Http\Requests\AreaFormRequest;


class AreaController extends SystemController
{
    public function index(AreaFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Area::select([
                'id',
                'city_id',
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
                        ->orWhereHas('city', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                                ->orWhereHas('country', function ($q) use ($request) {
                                    $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                                });
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Country'),
                    __('City'),
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Status'),
                    __('Action')

                ])
                ->addColumn('country', function ($data) {
                    if (isset($data->city->country)) {
                        return $data->city->country->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('city_id', function ($data) {
                    if (isset($data->city)) {
                        return $data->city->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('name_ar')
                ->addColumn('name_en')

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.area.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.area.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.area.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
               
                ->addColumn('action', function ($data) {
                    $edit = '';
                    if (Helper::adminCan('admin.area.edit')) {
                        $edit = '<a href="' . route('admin.area.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                
                    return $edit;
                  
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Areas')
            ];

            $this->viewData['pageTitle'] = __('Areas');

            return $this->view('area.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Area'),
            'url' => route('admin.area.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Area'),
        ];

        $this->viewData['pageTitle'] = __('Create Area');

        return $this->view('area.create', $this->viewData);
    }

    public function store(AreaFormRequest $request)
    {
    
        $requestData = $request->all();

        $insertData = Area::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.area.create')
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
    public function edit(Area $area)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Area'),
            'url' => route('admin.area.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $area->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Area');
        $this->viewData['result'] = $area;

        return $this->view('area.create', $this->viewData);
    }


    public function update(AreaFormRequest $request, Area $area)
    {
     
        $requestData = $request->all();

        $updateData = $area->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.area.index')
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
    public function destroy(Area $area)
    {
        $area->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.area.index')
            ]
        );
    }
    public function approvedStatus(Area $area)
    {
        if ($area->status == 0) {
            $approved = 1;
        } elseif ($area->status == 1) {
            $approved = 0;
        }
        $updateData = $area->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.area.index')
            ]
        );
    }
}

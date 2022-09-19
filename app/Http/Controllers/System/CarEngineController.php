<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App;
use App\Helpers\Helper;
use App\Http\Controllers\System\SystemController;
use App\Http\Requests\CarEngineFormRequest;
use App\Models\CarEngine;
use App\Models\CarMade;
use App\Models\CarModel;

class CarEngineController extends SystemController
{
    public function index(CarEngineFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  CarEngine::select([
                'id',
                'car_model_id',
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
                    $query
                        ->where('name_ar', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('carModel', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                                ->orWhereHas('carMade', function ($q) use ($request) {
                                    $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                                        ->orWhereHas('category', function ($q) use ($request) {
                                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                                        });
                                });
                        });
                    // $eloquentData->whereHas('car_models', function ($builder) use ($model) { 
                    //     $query->where('car_model_id', $model->id);
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Head of Parent'),
                    __('Made'),
                    __('Model'),
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Status'),
                    __('Action')

                ])
                ->addColumn('category', function ($data) {
                    if (isset($data->carModel->carMade->category)) {
                        return $data->carModel->carMade->category->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })

                ->addColumn('made', function ($data) {
                    if (isset($data->carModel->carMade)) {
                        return $data->carModel->carMade->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('car_model_id', function ($data) {
                    if (isset($data->carModel)) {
                        return $data->carModel->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })

                ->addColumn('name_ar')
                ->addColumn('name_en')


                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.car-engine.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.car-engine.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.car-engine.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })


                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.car-engine.edit')) {
                        $edit = '<a href="' . route('admin.car-engine.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.car-engine.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.car-engine.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' CarEngines')
            ];

            $this->viewData['pageTitle'] = __('CarEngines');

            return $this->view('car_engine.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Engine'),
            'url' => route('admin.car-engine.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Engine'),
        ];

        $this->viewData['pageTitle'] = __('Create Engine');

        return $this->view('car_engine.create', $this->viewData);
    }

    public function store(CarEngineFormRequest $request)
    {
        $nameCheckAr = CarEngine::where('name_ar',  $request->name_ar)
            ->where('car_model_id', $request->car_model_id)->first();
        $nameCheckEn = CarEngine::where('name_en',  $request->name_en)
            ->where('car_model_id', $request->car_model_id)->first();
        if ($nameCheckAr || $nameCheckEn) {
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to add data name is aleady found')
            );
        }
        $requestData = $request->all();

        $requestData['status'] = 1;

        $insertData = CarEngine::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.car-engine.create')
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
    public function edit(CarEngine $carEngine)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Engine'),
            'url' => route('admin.car-engine.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $carEngine->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Engine');
        $this->viewData['result'] = $carEngine;

        return $this->view('car_engine.create', $this->viewData);
    }


    public function update(CarEngineFormRequest $request, CarEngine $carEngine)
    {
        // dd($request->all());
        $nameCheckAr = CarEngine::where('name_ar',  $request->name_ar)
            ->where('car_model_id', $request->car_model_id)->first();
        $nameCheckEn = CarEngine::where('name_en',  $request->name_en)
            ->where('car_model_id', $request->car_model_id)->first();
        if ($nameCheckAr) {
            if ($nameCheckAr->id != $carEngine->id) {
                return $this->response(
                    false,
                    11001,
                    __('Sorry, the system is unable to add data name is aleady found')
                );
            }
        }
        if ($nameCheckEn) {
            if ($nameCheckEn->id != $carEngine->id) {
                return $this->response(
                    false,
                    11001,
                    __('Sorry, the system is unable to add data name is aleady found')
                );
            }
        }
        $requestData = $request->all();

        $updateData = $carEngine->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.car-engine.index')
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
    public function destroy(CarEngine $carEngine)
    {
        $carEngine->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.car-engine.index')
            ]
        );
    }
    public function approvedStatus(CarEngine $carEngine)
    {
        if ($carEngine->status == 0) {
            $approved = 1;
        } elseif ($carEngine->status == 1) {
            $approved = 0;
        }
        $updateData = $carEngine->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.car-engine.index')
            ]
        );
    }
}

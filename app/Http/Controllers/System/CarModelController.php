<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\CarMade;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facadels\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\CarModelFormRequest;
use App\Models\Category;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class CarModelController extends SystemController
{
    public function index(CarModelFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  CarModel::select([
                'id',
                'car_made_id',
                'name_en',
                'name_ar',
                'slug',
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
                        ->orWhereHas('carMade', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                                ->orWhereHas('category', function ($q) use ($request) {
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
                    __('Head of Parent'),
                    __('Made'),
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Status'),
                    __('Action')

                ])
                ->addColumn('category', function ($data) {
                    if (isset($data->carMade->category)) {
                        return $data->carMade->category->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('car_made_id', function ($data) {
                    if (isset($data->carMade)) {
                        return $data->carMade->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('name_ar')
                ->addColumn('name_en')
                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.car-model.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.car-model.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.car-model.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })

                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.car-model.edit')) {
                        $edit = '<a href="' . route('admin.car-model.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.car-model.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.car-model.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;

                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' CarModels')
            ];

            $this->viewData['pageTitle'] = __('CarModels');

            return $this->view('car_model.index', $this->viewData);
        }
    }

    public function create()
    {
        // $data = Category::with('childrenRecursive')->where('id','<',10)->get();
        // dd(App\Helpers\Helper::createMenuTree($data));
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Model'),
            'url' => route('admin.car-model.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Model'),
        ];

        $this->viewData['pageTitle'] = __('Create Model');

        return $this->view('car_model.create', $this->viewData);
    }

    public function store(CarModelFormRequest $request)
    {
        // $nameCheckAr = CarModel::where('name_ar',  $request->name_ar)
        //     ->where('car_made_id', $request->car_made_id)->first();
        // $nameCheckEn = CarModel::where('name_en',  $request->name_en)
        //     ->where('car_made_id', $request->car_made_id)->first();
        // if ($nameCheckAr || $nameCheckEn) {
        //     return $this->response(
        //         false,
        //         11001,
        //         __('Sorry, the system is unable to add data name is aleady found')
        //     );
        // }
        $requestData = $request->all();

        $requestData['status'] = 1;
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();

        $insertData = CarModel::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.car-model.create')
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
    public function edit(CarModel $CarModel)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Model'),
            'url' => route('admin.car-model.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $CarModel->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Model');
        $this->viewData['result'] = $CarModel;

        return $this->view('car_model.create', $this->viewData);
    }


    public function update(CarModelFormRequest $request, CarModel $CarModel)
    {
        // $nameCheckAr = CarModel::where('name_ar',  $request->name_ar)
        //     ->where('car_made_id', $request->car_made_id)->first();
        // $nameCheckEn = CarModel::where('name_en',  $request->name_en)
        //     ->where('car_made_id', $request->car_made_id)->first();
        // if ($nameCheckAr) {
        //     if ($nameCheckAr->id != $CarModel->id) {
        //         return $this->response(
        //             false,
        //             11001,
        //             __('Sorry, the system is unable to add data name is aleady found')
        //         );
        //     }
        // }
        // if ($nameCheckEn) {
        //     if ($nameCheckEn->id != $CarModel->id) {
        //         return $this->response(
        //             false,
        //             11001,
        //             __('Sorry, the system is unable to add data name is aleady found')
        //         );
        //     }
        // }
        $requestData = $request->all();
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();

        $updateData = $CarModel->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.car-model.index')
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
    public function destroy(CarModel $CarModel)
    {
        $CarModel->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.car-model.index')
            ]
        );
    }
    public function approvedStatus(CarModel $carModel)
    {
        if ($carModel->status == 0) {
            $approved = 1;
        } elseif ($carModel->status == 1) {
            $approved = 0;
        }
        $updateData = $carModel->update([
            'status' => $approved
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

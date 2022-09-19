<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\CarMade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\CarMadeFormRequest;
use App\Models\Category;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class CarMadeController extends SystemController
{
    public function index(CarMadeFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  CarMade::select([
                'id',
                'category_id',
                'name_en',
                'name_ar',
                'slug',
                'status',
                'image',
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
                        ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Head of Parent'),
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Image'),
                    __('Status'),
                    __('Action')

                ])
                ->addColumn('category_id', function ($data) {
                    if (isset($data->category)) {
                        return $data->category->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('name_ar')
                ->addColumn('name_en')               
                ->addColumn('image', function ($data) {
                    if ($data->image == null) {
                        return '--';
                    }

                    return '<img src="' . Helper::path() . '/' .  $data->image . '" style="width: 40px; height: 40px;" />';
                })

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.car-made.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.car-made.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.car-made.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.car-made.edit')) {
                        $edit = '<a href="' . route('admin.car-made.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.car-made.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.car-made.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
                  
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' CarMades')
            ];

            $this->viewData['pageTitle'] = __('CarMades');

            return $this->view('car_made.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Made'),
            'url' => route('admin.car-made.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Made'),
        ];

        $this->viewData['pageTitle'] = __('Create Made');

        return $this->view('car_made.create', $this->viewData);
    }

    public function store(CarMadeFormRequest $request)
    {
        $nameCheckAr = CarMade::where('name_ar',  $request->name_ar)
            ->where('category_id', $request->category_id)->first();
        $nameCheckEn = CarMade::where('name_en',  $request->name_en)
            ->where('category_id', $request->category_id)->first();
        if ($nameCheckAr || $nameCheckEn) {
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to add data name is aleady found')
            );
        }
        $requestData = $request->all();
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("car-mades",  $request->file('image'));
        }
        $requestData['status'] = 1;
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();

        $insertData = CarMade::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.car-made.create')
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
    public function edit(CarMade $carMade)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Made'),
            'url' => route('admin.car-made.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $carMade->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Made');
        $this->viewData['result'] = $carMade;

        return $this->view('car_made.create', $this->viewData);
    }


    public function update(CarMadeFormRequest $request, CarMade $carMade)
    {
        $nameCheckAr = CarMade::where('name_ar',  $request->name_ar)
            ->where('category_id', $request->parent_id)->first();
        $nameCheckEn = CarMade::where('name_en',  $request->name_en)
            ->where('category_id', $request->parent_id)->first();
        if ($nameCheckAr) {
            if ($nameCheckAr->id != $carMade->id) {
                return $this->response(
                    false,
                    11001,
                    __('Sorry, the system is unable to add data name is aleady found')
                );
            }
        }
        if ($nameCheckEn) {
            if ($nameCheckEn->id != $carMade->id) {
                return $this->response(
                    false,
                    11001,
                    __('Sorry, the system is unable to add data name is aleady found')
                );
            }
        }
        $requestData = $request->all();
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("car-mades",  $request->file('image'));
        } else {
            $requestData['image'] =  $carMade->image;
        }
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();

        $updateData = $carMade->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.car-made.index')
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
    public function destroy(CarMade $carMade)
    {
        $carMade->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.car-made.index')
            ]
        );
    }
    public function approvedStatus(CarMade $carMade)
    {
        if ($carMade->status == 0) {
            $approved = 1;
        } elseif ($carMade->status == 1) {
            $approved = 0;
        }
        $updateData = $carMade->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.car-made.index')
            ]
        );
    }
}

<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\ManufacturerFormRequest;
use App\Models\Category;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class ManufacturerController extends SystemController
{
    public function index(ManufacturerFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Manufacturer::select([
                'id',
                'category_id',
                'name_en',
                'name_ar',
                'company_name',
                'status',
                'address',
                'website',
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
                        ->orWhere('company_name', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('address', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('website', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                                ->orWhereHas('parent', function ($q) use ($request) {
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
                    __('Parent'),
                    __('Category'),
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Status'),
                    __('Image'),
                    __('Action')

                ])

                ->addColumn('category_id', function ($data) {
                    $arrays = [];
                    if (isset($data->category)) {
                        // dd($data->category->id);
                        if ($data->category->parent_id == 0) {
                            return $data->category->{'name_' . App::getLocale()};
                        } else {

                            $parents = Category::find($data->category->id)->parents->reverse();
                            if (count($parents)) {
                                foreach ($parents as $parent) {
                                    if ($data->category->id != $parent->id) {
                                        $arrays[] = '<span style="float: right;"> - ' .$parent->{'name_' . App::getLocale()} .'</span>';
                                    }
                                }
                            }

                            return implode('<br>', $arrays);
                        }
                    }
                })
                ->addColumn('parent_id', function ($data) {
                    if (isset($data->category_id)) {
                        return $data->category->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('name_ar')
                ->addColumn('name_en')

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.manufacturer.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.manufacturer.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.manufacturer.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })

                ->addColumn('image', function ($data) {
                    if ($data->image == null) {
                        return '--';
                    }

                    return '<img src="' . Helper::path() . '/' .  $data->image . '" style="width: 40px; height: 40px;" />';
                })


                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.manufacturer.edit')) {
                        $edit = '<a href="' . route('admin.manufacturer.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.manufacturer.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.manufacturer.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Manufacturers')
            ];

            $this->viewData['pageTitle'] = __('Manufacturers');

            return $this->view('manufacturer.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Manufacturer'),
            'url' => route('admin.manufacturer.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Manufacturer'),
        ];

        $this->viewData['pageTitle'] = __('Create Manufacturer');

        return $this->view('manufacturer.create', $this->viewData);
    }

    public function store(ManufacturerFormRequest $request)
    {

        $requestData = $request->all();

        foreach ($requestData['category_id'] as $key => $value) {
            if ($value != null) {

                if ($request->file('image')) {
                    $requestData['image'] =  Storage::disk('public')->put("manufacturers/)",  $request->file('image'));
                }
                $requestData['category_id'] = $value;
                $insertData = Manufacturer::create($requestData);
                if (!$insertData) {
                    return $this->response(
                        false,
                        11001,
                        __('Sorry, the system is unable to add data')
                    );
                }
            }
        }
        return $this->response(
            true,
            200,
            __('Data has been added successfully'),
            [
                'url' => route('admin.manufacturer.create')
            ]
        );
    }
    public function edit(Manufacturer $manufacturer)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Manufacturer'),
            'url' => route('admin.manufacturer.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $manufacturer->{'name_' . App::getLocale()}]),
        ];
        $manufacturerCategories = Manufacturer::where('name_en', $manufacturer->name_en)
            ->where('name_ar', $manufacturer->name_ar)->pluck('category_id');
        // dd($manufacturerCategories);
        $this->viewData['pageTitle'] = __('Edit Manufacturer');
        $this->viewData['result'] = $manufacturer;
        $this->viewData['manufacturerCategories'] = $manufacturerCategories;

        return $this->view('manufacturer.create', $this->viewData);
    }


    public function update(ManufacturerFormRequest $request, Manufacturer $manufacturer)
    {
        $arrays = [];
        $requestData = $request->all();
        $manufacturerCategories = Manufacturer::where('name_en', $manufacturer->name_en)
            ->where('name_ar', $manufacturer->name_ar)->pluck('category_id')->toArray();
        // dd($manufacturerCategories);
        $result = array_diff($manufacturerCategories, $requestData['category_id']);
        foreach ($result as $key => $value) {
            $array = [];
            $array['deleted'] = $value;
            $arrays[] = $array;
            $manufacturerCategoryDelete = Manufacturer::where('name_en', $manufacturer->name_en)
                ->where('name_ar', $manufacturer->name_ar)->where('category_id', $value)->forceDelete();
        }
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("manufacturers/)",  $request->file('image'));
        } else {
            $requestData['image'] =  $manufacturer->image;
        }
        // $requestData['name_ar'] = $manufacturer->name_ar;
        // $requestData['name_en'] = $manufacturer->name_en;

        foreach ($requestData['category_id'] as $key => $value) {
            if ($value != null) {
                $array = [];
                $manufacturerCategory = Manufacturer::where('name_en', $manufacturer->name_en)
                    ->where('name_ar', $manufacturer->name_ar)->where('category_id', $value)->first();

                if ($manufacturerCategory) {
                    $array['update'] = $value;
                    $array['manufacturerCategory'] = $manufacturerCategory;
                } else {
                    $array['create'] = $value;
                }
                $arrays[] = $array;
            }
        }
        // dd($manufacturer);
        foreach ($arrays as $key => $value) {
            if (isset($value['create'])) {
                $requestData['category_id'] = $value['create'];
                $insertData = Manufacturer::create($requestData);
                if (!$insertData) {
                    return $this->response(
                        false,
                        11001,
                        __('Sorry, the system is unable to add data')
                    );
                }
            } elseif (isset($value['update'])) {
                // $requestData['category_id'] = $manufacturer->category_id;
                unset($requestData['category_id']);

                $updateData = $manufacturer->update($requestData);

                $updateName =  $value['manufacturerCategory']->update([
                    'name_ar' => $requestData['name_ar'],
                    'name_en' => $requestData['name_en'],
                ]);

                if (!$updateData) {
                    return $this->response(
                        false,
                        11001,
                        __('Sorry, the system is unable to modify the data')
                    );
                }
                if (!$updateName) {
                    return $this->response(
                        false,
                        11001,
                        __('Sorry, the system is unable to modify the data')
                    );
                }
            }
        }
        return $this->response(
            true,
            200,
            __('Data has been added successfully'),
            [
                'url' => route('admin.manufacturer.index')
            ]
        );
    }
 
    public function show()
    {
        abort(404);
    }
    public function destroy(Manufacturer $manufacturer)
    {
        $manufacturer->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.manufacturer.index')
            ]
        );
    }
    public function approvedStatus(Manufacturer $manufacturer)
    {
        if ($manufacturer->status == 0) {
            $approved = 1;
        } elseif ($manufacturer->status == 1) {
            $approved = 0;
        }
        $updateData = $manufacturer->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.manufacturer.index')
            ]
        );
    }
}

<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
// use App;
use App\Helpers\Helper;
use App\Http\Requests\CategoryFormRequest;
use App\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

class CategoryController extends SystemController
{
    public function index(categoryFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Category::select([
                'id',
                'parent_id',
                // 'name_' . App::getLocale() . ' as name',
                'name_en',
                'name_ar',
                'slug',
                'status',
                'image',
                'created_at'
            ])->with('parent');
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
                if ($request->name == 'قسم رئيسي') {
                    $eloquentData->where(function ($query) {
                        $query->where('parent_id', '=', 0);
                    });
                } else {
                    $eloquentData->where(function ($query) use ($request) {
                        $query->where('name_ar', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('slug', 'LIKE', '%' . $request->name . '%')
                            ->orWhereHas('parent', function ($q) use ($request) {
                                $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                    ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                            });
                    });
                }
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Parent'),
                    __('Sub Categories'),
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Image'),
                    __('Status'),
                    __('Action')

                ])
                ->addColumn('parent_id', function ($data) {
                    $value = '--';
                    $parents = Category::find($data->id)->parents->reverse();
                    foreach ($parents as $parent) {
                        if ($parent->parent_id == 0) {
                            $value = $parent->{'name_' . app::getlocale()};
                        }
                    }
                    return $value;
                })
                ->addColumn('parents', function ($data) {
                    $arrays = [];


                    $parents = Category::find($data->id)->parents->reverse();
                    foreach ($parents as $parent) {
                        if ($parent->parent_id != 0) {
                            $arrays[] = '<span style="float: right;"> - ' . $parent->{'name_' . app::getlocale()} . '</span>';
                        }
                    }
                    return implode('<br>', $arrays);
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
                    if (Helper::adminCan('admin.category.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.category.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.category.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
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
                    if (Helper::adminCan('admin.category.edit')) {
                        $edit = '<span><a href="' . route('admin.category.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.category.show')) {
                        $childs = Category::where('parent_id', $data->id)->get();
                        if (count($childs)) {
                            $show = '<a href="' . route('admin.category.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-file-tree"></i></a></span>';
                        } else {
                            $show = '<span style="padding: 0 24px 0 0;"></span>';
                        }
                    }
                    if (Helper::adminCan('admin.category.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.category.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $show . $edit . $delete;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Categories')
            ];

            $this->viewData['pageTitle'] = __('Categories');

            return $this->view('category.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Category'),
            'url' => route('admin.category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Category'),
        ];

        $this->viewData['pageTitle'] = __('Create Category');

        return $this->view('category.create', $this->viewData);
    }

    public function store(CategoryFormRequest $request)
    {
        $requestData = $request->all();

        foreach ($requestData['parent_id'] as $key => $value) {
            if ($value != "null") {
                $parent_id = $value;
            }
        }
        $nameCheckAr = Category::where('name_ar',  $request->name_ar)
            ->where('parent_id', $parent_id)->first();
        $nameCheckEn = Category::where('name_en',  $request->name_en)
            ->where('parent_id', $parent_id)->first();
        if ($nameCheckAr || $nameCheckEn) {
            // dd($nameCheckAr);
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to add data name is aleady found')
            );
        }
        $category = Category::where('parent_id', $parent_id)->where('order', $request->order)->first();
        if ($category && $request->order != null) {
            return $this->response(
                false,
                11001,
                __('Sorry, it is not possible to add. I have already selected this arrangement for the same section')
            );
        }
        // dd($requestData);
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("categories",  $request->file('image'));
        }


        $requestData['parent_id'] = $parent_id;
        $requestData['status'] = 1;

        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();

        $insertData = Category::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.category.create')
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
    public function edit(Category $category)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Category'),
            'url' => route('admin.category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $category->{'name_' . App::getLocale()}]),
        ];
        $arrayParents = [];
        $parents = $category->parents->reverse();
        foreach ($parents as $parent) {
            $arrayParent = [];
           
                $arrayParent['name'] = $parent->{'name_' . App::getLocale()};
                $arrayParent['id'] = $parent->id;
                $arrayParents[] = $arrayParent;
            
        }
        $this->viewData['pageTitle'] = __('Edit Category');
        $this->viewData['result'] = $category;
        $this->viewData['arrayParents'] = $arrayParents;

        return $this->view('category.create', $this->viewData);
    }


    public function update(CategoryFormRequest $request, Category $category)
    {

        $requestData = $request->all();
        foreach ($requestData['parent_id'] as $key => $value) {
            if ($value != "null") {
                $parent_id = $value;
            }
        }
        $nameCheckAr = Category::where('name_ar',  $request->name_ar)
            ->where('parent_id', $parent_id)->first();
        $nameCheckEn = Category::where('name_en',  $request->name_en)
            ->where('parent_id', $parent_id)->first();
        if ($nameCheckAr) {
            if ($nameCheckAr->id != $category->id) {
                return $this->response(
                    false,
                    11001,
                    __('Sorry, the system is unable to add data name is aleady found')
                );
            }
        }
        if ($nameCheckEn) {
            if ($nameCheckEn->id != $category->id) {
                return $this->response(
                    false,
                    11001,
                    __('Sorry, the system is unable to add data name is aleady found')
                );
            }
        }
        $categoryCheck = Category::where('parent_id', $parent_id)->where('order', $request->order)->where('id', '!=', $category->id)->first();
        if ($categoryCheck) {
            return $this->response(
                false,
                11001,
                __('Sorry, it is not possible to add. I have already selected this order for the same section')
            );
        }
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("categories",  $request->file('image'));
        } else {
            $requestData['image'] =  $category->image;
        }
        if ($parent_id == null) {
            $parent_id = $category->parent_id;
        }
        $requestData['parent_id'] = $parent_id;
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();

        $updateData = $category->update($requestData);

        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.category.index')
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

    public function show(Category $category)
    {
        // Main View Vars
        $cat_name = Category::where('id', $category->id)->value('name_' . App::getLocale());
        $this->viewData['breadcrumb'][] = [
            'text' => __('Category'),
            'url' => route('admin.category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => $category->id,
        ];

        $this->viewData['pageTitle'] =  __('List') . '  ' . __('Category') . '  ' . $cat_name;

        $categories = Category::where('id', $category->id)->orderByRaw('ISNULL(-categories.order)', 'DESC')->orderBy('order', 'ASC')->get();
        $childs = Category::where('parent_id', $category->id)->orderByRaw('ISNULL(-categories.order)', 'DESC')->orderBy('order', 'ASC')->get();
        
        $this->viewData['categories'] = $categories;
        $this->viewData['childs'] = $childs;

        return $this->view('category.show', $this->viewData);
    }
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.category.index')
            ]
        );
    }
    public function getChilds($id)
    {

        $getParents = Category::where('parent_id', $id)->orderBy('order', 'DESC')->get(['id', 'name_ar']);
        if (count($getParents)) {
            $itemsArrays = [];
            foreach ($getParents as $item) {
                $itemArray = [];
                $itemArray['id'] = $item->id;
                $itemArray['name_ar'] = $item->{'name_' . app::getlocale()};
                $getParent = Category::where('parent_id', $item->id)->first(['id']);
                if ($getParent) {
                    $itemArray['parent'] = 1;
                } else {
                    $itemArray['parent'] = 0;
                }
                $itemsArrays[] = $itemArray;
            }
            return $this->response(
                true,
                200,
                __('Data has been deleted successfully'),
                $itemsArrays
            );
        } else {
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to get the data')
            );
        }
    }


    public function getcategoryByid($id)
    {
        $categories = Category::where('parent_id', $id)->pluck('order')->toArray();
        $imploadeCategories = implode(',', array_filter($categories));
        // dd();
        if ($categories) {
            return $this->response(
                true,
                200,
                __('Data has been get successfully'),
                $imploadeCategories
            );
        } else {
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to get the data')
            );
        }
    }
    public function approvedStatus(Category $category)
    {
        if ($category->status == 0) {
            $approved = 1;
        } elseif ($category->status == 1) {
            $approved = 0;
        }
        $updateData = $category->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.category.index')
            ]
        );
    }
}
